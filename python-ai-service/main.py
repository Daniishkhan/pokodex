from Scraper import wikiScraper
import requests
from typing import Union
from fastapi import FastAPI, exceptions
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()

origins = [
    "http://localhost.tiangolo.com",
    "https://localhost.tiangolo.com",
    "http://localhost",
    "http://localhost:8000",
    "http://localhost:3000"
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/pokemond-data")
def getData():
    try:
        url = "https://en.wikipedia.org/wiki/List_of_generation_I_Pok%C3%A9mon"
        scraper = wikiScraper.PokemonScraper()
        data = scraper.scrape_wikipedia_selenium(url)
        print("data from main", data)
        return {"data": data}
    except Exception as e:
        raise exceptions.HTTPException(status_code=500, detail=str(e))