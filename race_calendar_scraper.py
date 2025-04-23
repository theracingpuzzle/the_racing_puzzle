import requests
from bs4 import BeautifulSoup
import pandas as pd
import re
from datetime import datetime
import time

def scrape_racing_calendar():
    url = "https://www.attheraces.com/racing-calendar"
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
    }
    
    try:
        response = requests.get(url, headers=headers)
        response.raise_for_status()
    except requests.exceptions.RequestException as e:
        print(f"Error fetching the website: {e}")
        return None
    
    soup = BeautifulSoup(response.text, 'html.parser')
    
    # Find all day panels
    day_panels = soup.find_all('article', class_='panel push-x-x-small calendar-filter-day')
    
    all_races = []
    
    for panel in day_panels:
        # Extract date from header
        date_header = panel.find('h1', class_='h6')
        if not date_header:
            continue
            
        date_str = date_header.text.strip()
        try:
            # Parse date string with various ordinal suffixes
            date_str_clean = re.sub(r'(\d+)(st|nd|rd|th)', r'\1', date_str)
            date_obj = datetime.strptime(date_str_clean, "%d %B %Y")
        except ValueError as e:
            print(f"Date parsing error: {e} for '{date_str}'")
            # If format fails, just use the string
            date_obj = date_str
        
        # Format date as yyyy-mm-dd if it's a datetime object
        if isinstance(date_obj, datetime):
            formatted_date = date_obj.strftime("%Y-%m-%d")
        else:
            formatted_date = date_str
            
        # Find all race items for this day
        race_items = panel.find_all('li', class_='calendar-filter-item')
        
        for item in race_items:
            # Extract full text content
            full_text = item.get_text(strip=True)
            
            # Extract racecourse name and details
            small_tag = item.find('small')
            
            # Initialize variables
            racecourse_name = ""
            time_of_day = ""
            surface = ""
            race_type = ""
            
            if small_tag:
                details = small_tag.text.strip()
                racecourse_name = full_text.replace(details, '').strip()
                
                # Process the details to extract the information
                # Pattern for details like: (Afternoon) (Turf) (Jumps)
                pattern = r'\(([^)]+)\)'
                matches = re.findall(pattern, details)
                
                # Assign matches to appropriate categories based on position and expected values
                for i, match in enumerate(matches):
                    match = match.strip()
                    if i == 0 and match in ["Afternoon", "Evening"]:
                        time_of_day = match
                    elif i == 1 and match in ["Turf", "AW"]:
                        surface = match
                    elif i == 2 and match in ["Flat", "Jumps"]:
                        race_type = match
            else:
                racecourse_name = full_text
                
            # Extract country from classes
            classes = item.get('class', [])
            country = "UK" if "uk" in classes else "Ireland" if "ire" in classes else "Unknown"
                
            # Format the racecourse string with the details
            racecourse_with_details = f"{racecourse_name} ({time_of_day}) ({surface}) ({race_type})"
            
            # Create race data dictionary
            race_data = {
                'Date': formatted_date,
                'Racecourse': racecourse_with_details,
                'Time of Day': time_of_day,
                'Surface': surface,
                'Race Type': race_type,
                'Country': country
            }
            
            all_races.append(race_data)
    
    return pd.DataFrame(all_races)

def save_to_csv(df, filename="racing_calendar.csv"):
    if df is not None and not df.empty:
        df.to_csv(filename, index=False)
        print(f"Data successfully saved to {filename}")
    else:
        print("No data to save.")

def main():
    print("Scraping AtTheRaces racing calendar...")
    df = scrape_racing_calendar()
    
    if df is not None:
        print(f"Found {len(df)} races.")
        print(df.head())
        save_to_csv(df)
    else:
        print("Failed to retrieve data.")

if __name__ == "__main__":
    main()