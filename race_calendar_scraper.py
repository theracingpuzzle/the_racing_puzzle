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
            # Convert date string to datetime object
            date_obj = datetime.strptime(date_str, "%dst %B %Y")
        except ValueError:
            try:
                date_obj = datetime.strptime(date_str, "%dnd %B %Y")
            except ValueError:
                try:
                    date_obj = datetime.strptime(date_str, "%drd %B %Y")
                except ValueError:
                    try:
                        date_obj = datetime.strptime(date_str, "%dth %B %Y")
                    except ValueError:
                        # If all formats fail, just use the string
                        date_obj = date_str
        
        # Format date as yyyy-mm-dd if it's a datetime object
        if isinstance(date_obj, datetime):
            formatted_date = date_obj.strftime("%Y-%m-%d")
        else:
            formatted_date = date_str
            
        # Find all race items for this day
        race_items = panel.find_all('li', class_='calendar-filter-item')
        
        for item in race_items:
            # Extract racecourse name
            racecourse = item.get_text().split('<small>')[0].strip()
            
            # Extract details from small tag
            small_tag = item.find('small')
            if small_tag:
                details = small_tag.text.strip()
                
                # Parse details inside parentheses
                time_of_day = re.search(r'\(([^)]*)\)', details)
                time_of_day = time_of_day.group(1) if time_of_day else ""
                
                surface = re.search(r'\([^)]*\) \(([^)]*)\)', details)
                surface = surface.group(1) if surface else ""
                
                race_type = re.search(r'\([^)]*\) \([^)]*\) \(([^)]*)\)', details)
                race_type = race_type.group(1) if race_type else ""
            else:
                time_of_day = ""
                surface = ""
                race_type = ""
                
            # Extract classes for additional information
            classes = item.get('class', [])
            country = "UK" if "uk" in classes else "Ireland" if "ire" in classes else "Unknown"
                
            all_races.append({
                'Date': formatted_date,
                'Racecourse': racecourse,
                'Time of Day': time_of_day,
                'Surface': surface,
                'Race Type': race_type,
                'Country': country
            })
    
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