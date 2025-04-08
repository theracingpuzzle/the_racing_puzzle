from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
import time

chrome_options = Options()
chrome_options.add_argument("--headless")  # Run in the background
service = Service("/path/to/chromedriver")  # Set your chromedriver path
driver = webdriver.Chrome(service=service, options=chrome_options)

url = "https://www.bbc.co.uk/sport/horse-racing/calendar"
driver.get(url)
time.sleep(5)  # Wait for JavaScript to load

page_html = driver.page_source
print(page_html[:1000])  # Preview the content

driver.quit()
