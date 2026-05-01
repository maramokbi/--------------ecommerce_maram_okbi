from fastapi import FastAPI, HTTPException
import json

app = FastAPI()

@app.get("/read-json/")
def read_json_file():
    try:
        # Ouvrir et lire le fichier JSON
        with open("data.json", "r", encoding="utf-8") as f:
            data = json.load(f)

        # Extraire les titres
        titres = data.get("titles", [])

        return {"titres": titres}

    except FileNotFoundError:
        raise HTTPException(status_code=404, detail="Fichier data.json introuvable")