# Import required modules
from lxml import html
import requests
import json

# Request the page
def scrape_chapters(pokemons):
    data = []
    for pokemon in pokemons:
        url='https://pokemondb.net/pokedex/'+str(pokemon)+'/moves/1'
        page = requests.get(url)
        tree = html.fromstring(page.content)
        lvlupMoves = tree.xpath('/html/body/main/div[2]/div[2]/div[1]/div/div[1]/div/table/tbody/tr/td[2]/a')
        lvlupMovesList = []
        for item in lvlupMoves:
            lvlupMovesList.append(item.text_content())
        hmMoves = tree.xpath('/html/body/main/div[2]/div[2]/div[1]/div/div[2]/div[1]/table/tbody/tr/td[2]/a')
        hmMovesList = []
        for item in hmMoves:
            hmMovesList.append(item.text_content())
        tmMoves = tree.xpath('/html/body/main/div[2]/div[2]/div[1]/div/div[2]/div[2]/table/tbody/tr/td[2]/a')
        tmMovesList = []
        for item in tmMoves:
            tmMovesList.append(item.text_content())
        data.append({"pokemon": pokemon, "level up moves": lvlupMovesList, "hm moves": hmMovesList, "tm moves": tmMovesList})
        json.dump( data, open( "gen1LearnSet.json", 'w' ) )

# Parsing the page
# (We need to use page.content rather than 
# page.text because html.fromstring implicitly
# expects bytes as input.)

# Get element using XPath


# import json
# from urllib.request import Request, urlopen
# from bs4 import BeautifulSoup
# import time

# print("scraping chapters")
# def scrape_chapters(pokemons):
# # list of chapter objects
# # chapter objects are {chapter}
#     data = []
#     # try:
#     for pokemon in pokemons:
#         req = Request(
#             url='https://pokemondb.net/pokedex/'+str(pokemon)+'/moves/1', 
#             headers={'User-Agent': 'Mozilla/5.0'}
#         )
#         try:
#             webpage = urlopen(req)
#         except:
#             print("timed out, sleeping, reached "+data[-1]["chapter"])
#             time.sleep(10)
#             print("continuing")
#             webpage = urlopen(req)
#         bs1 = BeautifulSoup(webpage.read(), 'html.parser')
#         print(bs1)
    #     chapter = bs1.find("span", class_="chapter-title")
    #     content = bs1.find(id="chapter-container")
    #     print("content:", content)
    #     print("content decoded: ", content.decode("utf8"))
    #     data.append({"chapter": chapter.text, "content": str(content)})      
    # json.dump( data, open( file_name, 'w' ) )
    # except Exception as error:
    #     print (error)
    #     #[{"chapter": "Chapter 1: Foreigners", "content": 
    #     print("timeout failed, saving progress under "+"incomplete_"+file_name)
    #     json.dump( data, open( "incomplete_"+file_name, 'w' ) )

scrape_chapters(["Bulbasaur"
,"Ivysaur"
,"Venusaur"
,"VenusaurMega"
,"Charmander"
,"Charmeleon"
,"Charizard"
,"CharizardMega"
,"CharizardMega"
,"Squirtle"
,"Wartortle"
,"Blastoise"
,"BlastoiseMega"
,"Caterpie"
,"Metapod"
,"Butterfree"
,"Weedle"
,"Kakuna"
,"Beedrill"
,"BeedrillMega"
,"Pidgey"
,"Pidgeotto"
,"Pidgeot"
,"PidgeotMega"
,"Rattata"
,"Raticate"
,"Spearow"
,"Fearow"
,"Ekans"
,"Arbok"
,"Pikachu"
,"Raichu"
,"Sandshrew"
,"Sandslash"
,"Nidoran♀"
,"Nidorina"
,"Nidoqueen"
,"Nidoran♂"
,"Nidorino"
,"Nidoking"
,"Clefairy"
,"Clefable"
,"Vulpix"
,"Ninetales"
,"Jigglypuff"
,"Wigglytuff"
,"Zubat"
,"Golbat"
,"Oddish"
,"Gloom"
,"Vileplume"
,"Paras"
,"Parasect"
,"Venonat"
,"Venomoth"
,"Diglett"
,"Dugtrio"
,"Meowth"
,"Persian"
,"Psyduck"
,"Golduck"
,"Mankey"
,"Primeape"
,"Growlithe"
,"Arcanine"
,"Poliwag"
,"Poliwhirl"
,"Poliwrath"
,"Abra"
,"Kadabra"
,"Alakazam"
,"AlakazamMega"
,"Machop"
,"Machoke"
,"Machamp"
,"Bellsprout"
,"Weepinbell"
,"Victreebel"
,"Tentacool"
,"Tentacruel"
,"Geodude"
,"Graveler"
,"Golem"
,"Ponyta"
,"Rapidash"
,"Slowpoke"
,"Slowbro"
,"SlowbroMega"
,"Magnemite"
,"Magneton"
,"Farfetch"
,"Doduo"
,"Dodrio"
,"Seel"
,"Dewgong"
,"Grimer"
,"Muk"
,"Shellder"
,"Cloyster"
,"Gastly"
,"Haunter"
,"Gengar"
,"GengarMega"
,"Onix"
,"Drowzee"
,"Hypno"
,"Krabby"
,"Kingler"
,"Voltorb"
,"Electrode"
,"Exeggcute"
,"Exeggutor"
,"Cubone"
,"Marowak"
,"Hitmonlee"
,"Hitmonchan"
,"Lickitung"
,"Koffing"
,"Weezing"
,"Rhyhorn"
,"Rhydon"
,"Chansey"
,"Tangela"
,"Kangaskhan"
,"KangaskhanMega"
,"Horsea"
,"Seadra"
,"Goldeen"
,"Seaking"
,"Staryu"
,"Starmie"
,"Mr"
,"Scyther"
,"Jynx"
,"Electabuzz"
,"Magmar"
,"Pinsir"
,"PinsirMega"
,"Tauros"
,"Magikarp"
,"Gyarados"
,"GyaradosMega"
,"Lapras"
,"Ditto"
,"Eevee"
,"Vaporeon"
,"Jolteon"
,"Flareon"
,"Porygon"
,"Omanyte"
,"Omastar"
,"Kabuto"
,"Kabutops"
,"Aerodactyl"
,"AerodactylMega"
,"Snorlax"
,"Articuno"
,"Zapdos"
,"Moltres"
,"Dratini"
,"Dragonair"
,"Dragonite"
,"Mewtwo"
,"MewtwoMega"
,"MewtwoMega"
,"Mew"])