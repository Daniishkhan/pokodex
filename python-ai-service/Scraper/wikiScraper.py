import requests
import time
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from chromedriver_py import binary_path
from bs4 import BeautifulSoup

class PokemonScraper:
    def __init__(self):
        pass

    def scrape_wikipedia_selenium(self, url):
        chrome_options = Options()
        chrome_options.add_argument("--headless")
        chrome_options.add_argument("--no-sandbox")
        chrome_options.add_argument("--disable-dev-shm-usage")

        svc = Service(executable_path=binary_path)
        driver = webdriver.Chrome(service=svc, options=chrome_options)

        driver.get(url)

        time.sleep(5)

        html = driver.page_source

        soup = BeautifulSoup(html, "html.parser")

        driver.quit()

        # Rest of the method remains the same
        tables = soup.find_all("table", class_="wikitable sortable plainrowheaders jquery-tablesorter")
        print(f"Number of tables found: {len(tables)}")

        if tables:
            for i, table in enumerate(tables):
                rows = table.find("tbody").find_all("tr")
                all_pokemon = []

                for row in rows:
                    name_cell = row.find("th", scope="row")
                    if name_cell:
                        name = name_cell.get_text(strip=True)
                    else:
                        continue

                    type_cell = row.find_all("td")[1]
                    type_ = type_cell.get_text(strip=True)

                    evolves_from = row.find_all("td")[2].get_text(strip=True) if len(row.find_all("td")) > 2 else "N/A"
                    evolves_into = row.find_all("td")[3].get_text(strip=True) if len(row.find_all("td")) > 3 else "N/A"

                    notes = row.find_all("td")[4].get_text(strip=True) if len(row.find_all("td")) > 4 else "N/A"

                    pokemon = {
                        "name": name,
                        "type": type_,
                        "evolves_from": evolves_from,
                        "evolves_into": evolves_into,
                        "notes": notes
                    }
                    all_pokemon.append(pokemon)

                return all_pokemon
        else:
            print("No tables found with the specified class.")
            return []