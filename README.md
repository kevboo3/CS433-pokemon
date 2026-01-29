# CS433 Project 3: Pokémon Game

## Project Description
The Goal was to create a Pokémon game using what we have learned up until this point (PHP, JavaScript, CSS, SQL, HTML , etc.) 
with some additional project requirements.

Our version of a Pokémon game aimed be a [roguelike](https://en.wikipedia.org/wiki/Roguelike). Think something 
similar to [PokeRogue](https://pokerogue.net/) where items, starting Pokémon, trainers, and route encounters are all randomized.

## How was it made?
XAMPP was used for hosting a local PHP and SQL server for playing the game.

PHP queried the database as well as generating the page once loaded.

JavaScript allowed for user interaction with the Pokémon selection and batting user interface.

CSS helped the UI elements look visually appealing.

## How to self host and run the game (assuming you are running windows)
1. Download [XAMPP](https://www.apachefriends.org/download.html) and run the installer
2. Make a folder named proj3 inside htdocs folder. The htdocs folder can be found in C:\xampp\htdocs\
3. [Download](https://github.com/kevboo3/CS433-pokemon/archive/refs/heads/main.zip) the zip file of this repository you are reading
4. Extract the zip file and paste the folder CS433-pokemon into htdocs
5. Open XAMPP Control Panel and click the "Start" button for Apache and the "Start" button for MySQL.<img width="671" height="410" alt="Screenshot_1" src="https://github.com/user-attachments/assets/95474f43-0a13-4aa8-b882-47c683cb1c19" />
6. Go to the address http://localhost/proj3/CS433-pokemon-main/ in your browser (preferably chrome)
7. Click ```proj3.php``` to start the game.
<img width="653" height="459" alt="how to start game" src="https://github.com/user-attachments/assets/22671de6-2ffc-4426-9f45-abc8d928d75c" />

## How to play
Start Menu:
- Menu gives users to enable sound, adjust the volume, and of course and start the game
  <img width="1905" height="913" alt="start menu" src="https://github.com/user-attachments/assets/1f949aa1-c6bd-4965-be39-b008a1bf622d" />

Select Team:
- You will be given 6 random Pokémon
- Click on Pokémon to lock in the ones you want for your team (highlighted in green)
- Rerolling will replace the unlocked Pokémon with random ones
- When satisfied with the 6 on screen you can click confirm team
<img width="1919" height="906" alt="image" src="https://github.com/user-attachments/assets/7035cb75-39a1-4d8a-ad6a-880feaa70be9" />
<img width="1919" height="906" alt="team roll" src="https://github.com/user-attachments/assets/9e891c32-a3d1-43eb-9f09-9622dc033590" />

Select Moves:
- Click on the Pokémon you want to change moves on
- Use the drop boxes to choose
<img width="1919" height="901" alt="select move1" src="https://github.com/user-attachments/assets/65745835-fee7-4203-8a3f-164e34cfa84c" />
<img width="1919" height="897" alt="select move 2" src="https://github.com/user-attachments/assets/04ca508f-00e1-4799-a132-d23fd3f94596" />

Battle:
- Click on the active Pokémons move to attack the enemy
- Click on a the name of the Pokémon party members to switch to it
- Attack the oppenents pokemon without dying!
<img width="1523" height="904" alt="image" src="https://github.com/user-attachments/assets/36133014-f1f5-487a-b201-a6258a2c92fe" />
<img width="1522" height="899" alt="image" src="https://github.com/user-attachments/assets/d0f1afdb-9cbd-4cb4-99da-e1321e0f736e" />



## Audio
None of the audio used is of our own, but it made a nice addition to our project.

### Artists/IP holders
- Final Fantasy 15 audio: Yoko Shimomura
- Pokémon: Nintendo
- Octopath Music: Yasunori Nishiki

## Authors
- [@kevboo3](https://github.com/kevboo3)
- [@JustinC1620](https://github.com/JustinC1620)
- [@TobiasHazoume](https://github.com/TobiasHazoume)
- [@20sarah20](https://github.com/20sarah20)
- [@atanerm](https://github.com/atanerm)
