<?php
// North America - US, Canada and Mexico

$Pacific = new Continent(
name: "Pacific Region",
territories: [
    "Anchorage",
    "Honolulu",
    "Vancouver",
    "Seattle",
    "Portland",
    "Sacramento",
    "San Francisco",
    "Los Angeles",
    "San Diego",
    "Boise"
    ]
);

$Northwest = new Continent(
    name: "Northwest Region",
    territories: [
        "Billings",
        "Edmonton",
        "Sioux Falls",
        "Winnipeg",
        "Minneapolis",
        "Fargo",
        "Cheyenne",
        "Regina",
        "Calgary",
        "Thunder Bay"
    ]
);

$Gulf = new Continent(
    name: "Gulf Region",
    territories: [
        "Mérida",
        "Monterrey",
        "Guadalajara",
        "Tampa",
        "Houston",
        "Austin",
        "Miami",
        "Mobile",
        "New Orleans",
        "Baton Rouge",
        "San Antonio",
        "Mexico City"
    ]
);

$Southwest = new Continent(
    name: "Southwest Region",
    territories: [
        "Salt Lake City",
        "Ciudad Juárez",
        "Denver",
        "Santa Fe",
        "Phoenix",
        "Las Vegas",
        "Colorado Springs",
        "Tucson",
        "Hermosillo",
        "Chihuahua"
    ]
);

$Central = new Continent(
    name: "Central Region",
    territories: [
        "Dallas",
        "Oklahoma City",
        "Kansas City",
        "St. Louis",
        "Des Moines",
        "Omaha",
        "Wichita",
        "Branson"
    ]
);

$Midwest = new Continent(
    name: "Midwest Region",
    territories: [
        "Columbus",
        "Indianapolis",
        "Cincinnati",
        "Cleveland",
        "Chicago",
        "Detroit",
        "Milwaukee",
        "Springfield",
        "Grand Rapids"
    ]
);

$Northeast = new Continent(
    name: "Northeast Region",
    territories: [
        "Quebec City",
        "Montreal",
        "Boston",
        "Providence",
        "Toronto",
        "Manchester",
        "Burlington",
        "Augusta",
        "Ottawa",
        "Greater Sudbury"
    ]
);

$East = new Continent(
    name: "Atlantic Region",
    territories: [
        "Long Island",
        "Washington",
        "New York",
        "Albany",
        "Hartford",
        "Philadelphia",
        "Pittsburgh",
        "Richmond",
        "Norfolk",
        "Buffalo"
    ]
);

$South = new Continent(
    name: "Southern Region",
    territories: [
        "Atlanta",
        "Huntington",
        "Memphis",
        "Louisville",
        "Charleston",
        "Jackson",
        "Nashville",
        "Charlotte",
        "Shreveport",
        "Birmingham"
    ]
);

$Anchorage = new Territory(
    name: "Anchorage",
    links: [
    "Honolulu",
    "Seattle"
    ]
    );

$Honolulu = new Territory(
    name: "Honolulu",
    links: [
    "San Francisco",
    "Anchorage"
    ]
    );

$Seattle = new Territory(
    name: "Seattle",
    links: [
    "Anchorage",
    "Vancouver",
    "Billings",
    "Portland"
    ]
    );

$Vancouver = new Territory(
    name: "Vancouver",
    links: [
    "Calgary",
    "Seattle"
    ]
    );

$Portland = new Territory(
    name: "Portland",
    links: [
    "Seattle",
    "Boise",
    "Sacramento"
    ]
    );

$Sacramento = new Territory(
    name: "Sacramento",
    links: [
    "Portland",
    "Salt Lake City",
    "San Francisco"
    ]
    );

$SanFrancisco = new Territory(
    name: "San Francisco",
    links: [
    "Sacramento",
    "Honolulu",
    "Los Angeles"
    ]
    );

$LosAngeles = new Territory(
    name: "Los Angeles",
    links: [
    "San Francisco",
    "Las Vegas",
    "Phoenix",
    "San Diego"
    ]
    );

$SanDiego = new Territory(
    name: "San Diego",
    links: [
    "Los Angeles",
    "Tucson"
    ]
    );

$Boise = new Territory(
    name: "Boise",
    links: [
    "Portland",
    "Salt Lake City"
    ]
    );


// end Pacific, start Northwest

$Calgary = new Territory(
    name: "Calgary",
    links: [
    "Vancouver",
    "Edmonton",
    "Regina"
    ]
    );

$Edmonton = new Territory(
    name: "Edmonton",
    links: [
    "Calgary",
    "Regina"
    ]
    );

$Regina = new Territory(
    name: "Regina",
    links: [
    "Calgary",
    "Edmonton",
    "Winnipeg"
    ]
    );

$Winnipeg = new Territory(
    name: "Winnipeg",
    links: [
    "Regina",
    "Fargo",
    "Thunder Bay"
    ]
    );

$ThunderBay = new Territory(
    name: "Thunder Bay",
    links: [
    "Winnipeg",
    "Greater Sudbury",
    ]
    );

$Fargo = new Territory(
    name: "Fargo",
    links: [
    "Winnipeg",
    "Billings",
    "Minneapolis",
    "Sioux Falls"
    ]
    );

$Billings = new Territory(
    name: "Billings",
    links: [
    "Seattle",
    "Fargo",
    "Sioux Falls",
    "Cheyenne"
    ]
    );

$Cheyenne = new Territory(
    name: "Cheyenne",
    links: [
    "Billings",
    "Salt Lake City",
    "Denver",
    "Omaha"
    ]
    );

$SiouxFalls = new Territory(
    name: "Sioux Falls",
    links: [
    "Billings",
    "Fargo",
    "Omaha",
    "Des Moines"
    ]
    );

$Minneapolis = new Territory(
    name: "Minneapolis",
    links: [
    "Fargo",
    "Milwaukee",
    "Des Moines"
    ]
    );

// end Northwest, start Southwest
$SaltLakeCity = new Territory(
    name: "Salt Lake City",
    links: [
    "Boise",
    "Sacramento",
    "Las Vegas",
    "Cheyenne"
    ]
    );

$LasVegas = new Territory(
    name: "Las Vegas",
    links: [
    "Salt Lake City",
    "Los Angeles"
    ]
    );

$Denver = new Territory(
    name: "Denver",
    links: [
    "Cheyenne",
    "Colorado Springs",
    "Wichita"
    ]
    );

$ColoradoSprings = new Territory(
    name: "Colorado Springs",
    links: [
    "Denver",
    "Santa Fe"
    ]
    );

$SantaFe = new Territory(
    name: "Santa Fe",
    links: [
    "Colorado Springs",
    "Ciudad Juárez"
    ]
    );

$CiudadJuarez = new Territory(
    name: "Ciudad Juárez",
    links: [
    "Tucson",
    "Santa Fe",
    "Chihuahua"
    ]
    );

$Phoenix = new Territory(
    name: "Phoenix",
    links: [
    "Los Angeles",
    "Tucson"
    ]
    );

$Tucson = new Territory(
    name: "Tucson",
    links: [
    "San Diego",
    "Phoenix",
    "Ciudad Juárez",
    "Hermosillo"
    ]
    );

$Hermosillo = new Territory(
    name: "Hermosillo",
    links: [
    "Tucson",
    "Chihuahua"
    ]
    );

$Chihuahua = new Territory(
    name: "Chihuahua",
    links: [
    "Hermosillo",
    "Ciudad Juárez",
    "Monterrey"
    ]
    );
// end Southwest, begin Central
$Omaha = new Territory(
    name: "Omaha",
    links: [
    "Cheyenne",
    "Sioux Falls",
    "Kansas City",
    "Des Moines"
    ]
    );

$DesMoines = new Territory(
    name: "Des Moines",
    links: [
    "Sioux Falls",
    "Minneapolis",
    "Omaha",
    "Kansas City"
    ]
    );

$KansasCity = new Territory(
    name: "Kansas City",
    links: [
    "Omaha",
    "Des Moines",
    "Wichita",
    "St. Louis"
    ]
    );

$StLouis = new Territory(
    name: "St. Louis",
    links: [
    "Kansas City",
    "Branson",
    "Springfield",
    ]
    );

$Wichita = new Territory(
    name: "Wichita",
    links: [
    "Denver",
    "Kansas City",
    "Oklahoma City"
    ]
    );

$Branson = new Territory(
    name: "Branson",
    links: [
    "St. Louis",
    "Memphis",
    "Oklahoma City"
    ]
    );

$OklahomaCity = new Territory(
    name: "Oklahoma City",
    links: [
    "Wichita",
    "Branson",
    "Dallas"
    ]
    );

$Dallas = new Territory(
    name: "Dallas",
    links: [
    "Oklahoma City",
    "Austin",
    "Houston",
    "Shreveport"
    ]
    );

// end Central, start Gulf
$Austin = new Territory(
    name: "Austin",
    links: [
    "Dallas",
    "San Antonio",
    ]
    );

$SanAntonio = new Territory(
    name: "San Antonio",
    links: [
    "Dallas",
    "Houston",
    "Monterrey"
    ]
    );

$Houston = new Territory(
    name: "Houston",
    links: [
    "Dallas",
    "San Antonio",
    "Baton Rouge",
    "Mérida"
    ]
    );

$BatonRouge = new Territory(
    name: "Baton Rouge",
    links: [
    "Shreveport",
    "Jackson",
    "New Orleans",
    "Houston"
    ]
    );

$NewOrleans = new Territory(
    name: "New Orleans",
    links: [
    "Baton Rouge",
    "Mobile",
    "Tampa",
    "Mérida"
    ]
    );

$Mobile = new Territory(
    name: "Mobile",
    links: [
    "New Orleans",
    "Birmingham",
    "Tampa",
    ]
    );

$Tampa = new Territory(
    name: "Tampa",
    links: [
    "New Orleans",
    "Mobile",
    "Miami",
    "Mérida"
    ]
    );

$Miami = new Territory(
    name: "Miami",
    links: [
    "Tampa",
    "Charleston",
    ]
    );

$Monterrey = new Territory(
    name: "Monterrey",
    links: [
    "Chihuahua",
    "San Antonio",
    "Guadalajara",
    "Mexico City"
    ]
    );

$Guadalajara = new Territory(
    name: "Guadalajara",
    links: [
    "Monterrey",
    "Mexico City"
    ]
    );

$MexicoCity = new Territory(
    name: "Mexico City",
    links: [
    "Monterrey",
    "Guadalajara",
    "Mérida"
    ]
    );

$Mérida = new Territory(
    name: "Mérida",
    links: [
    "Mexico City",
    "Houston",
    "New Orleans",
    "Tampa"
    ]
    );
// end Gulf, begin South
$Shreveport = new Territory(
    name: "Shreveport",
    links: [
    "Dallas",
    "Memphis",
    "Jackson",
    "Baton Rouge"
    ]
    );

$Jackson = new Territory(
    name: "Jackson",
    links: [
    "Shreveport",
    "Memphis",
    "Birmingham",
    "Baton Rouge"
    ]
    );

$Memphis = new Territory(
    name: "Memphis",
    links: [
    "Shreveport",
    "Jackson",
    "Branson",
    "Nashville"
    ]
    );

$Birmingham = new Territory(
    name: "Birmingham",
    links: [
    "Jackson",
    "Mobile",
    "Atlanta"
    ]
    );

$Atlanta = new Territory(
    name: "Atlanta",
    links: [
    "Birmingham",
    "Nashville",
    "Charlotte",
    "Charleston"
    ]
    );

$Charleston = new Territory(
    name: "Charleston",
    links: [
    "Miami",
    "Atlanta",
    "Charlotte"    ]
    );

$Charlotte = new Territory(
    name: "Charlotte",
    links: [
    "Atlanta",
    "Charleston",
    "Richmond",
    ]
    );

$Nashville = new Territory(
    name: "Nashville",
    links: [
    "Memphis",
    "Atlanta",
    "Louisville"
    ]
    );

$Louisville = new Territory(
    name: "Louisville",
    links: [
    "Nashville",
    "Indianapolis",
    "Cincinnati",
    "Huntington"
    ]
    );

$Huntington = new Territory(
    name: "Huntington",
    links: [
    "Louisville",
    "Columbus",
    ]
    );

// end South, start Midwest
$Milwaukee = new Territory(
    name: "Milwaukee",
    links: [
    "Minneapolis",
    "Chicago",
    "Grand Rapids"
    ]
    );

$Chicago = new Territory(
    name: "Chicago",
    links: [
    "Milwaukee",
    "Springfield",
    "Grand Rapids",
    "Indianapolis"
    ]
    );

$GrandRapids = new Territory(
    name: "Grand Rapids",
    links: [
    "Milwaukee",
    "Chicago",
    "Detroit"
    ]
    );

$Detroit = new Territory(
    name: "Detroit",
    links: [
    "Toronto",
    "Buffalo",
    "Cleveland",
    "Grand Rapids"
    ]
    );

$Springfield = new Territory(
    name: "Springfield",
    links: [
    "St. Louis",
    "Chicago",
    "Indianapolis"    ]
    );

$Indianapolis = new Territory(
    name: "Indianapolis",
    links: [
    "Chicago",
    "Springfield",
    "Louisville",
    "Cincinnati"    ]
    );

$Cincinnati = new Territory(
    name: "Cincinnati",
    links: [
    "Indianapolis",
    "Louisville",
    "Columbus"
    ]
    );

$Columbus = new Territory(
    name: "Columbus",
    links: [
    "Cincinnati",
    "Cleveland",
    "Pittsburgh",
    "Huntington"
    ]
    );

$Cleveland = new Territory(
    name: "Cleveland",
    links: [
    "Detroit",
    "Columbus",
    "Buffalo",
    "Pittsburgh"
    ]
    );
// end Midwest, begin Northeast
$GreaterSudbury = new Territory(
    name: "Greater Sudbury",
    links: [
    "Thunder Bay",
    "Toronto",
    "Ottawa"
    ]
    );

$Ottawa = new Territory(
    name: "Ottawa",
    links: [
    "Greater Sudbury",
    "Montreal",
    "Toronto"
    ]
    );

$Montreal = new Territory(
    name: "Montreal",
    links: [
    "Ottawa",
    "Quebec City",
    "Burlington"
    ]
    );

$QuebecCity = new Territory(
    name: "Quebec City",
    links: [
    "Montreal",
    "Augusta"
    ]
    );

$Toronto = new Territory(
    name: "Toronto",
    links: [
    "Greater Sudbury",
    "Ottawa",
    "Detroit",
    "Buffalo"
    ]
    );

$Burlington = new Territory(
    name: "Burlington",
    links: [
    "Montreal",
    "Manchester"
    ]
    );

$Augusta = new Territory(
    name: "Augusta",
    links: [
    "Quebec City",
    "Manchester"
    ]
    );

$Manchester = new Territory(
    name: "Manchester",
    links: [
    "Burlington",
    "Augusta",
    "Boston"
    ]
    );

$Boston = new Territory(
    name: "Boston",
    links: [
    "Albany",
    "Hartford",
    "Providence",
    "Manchester"
    ]
    );

$Providence = new Territory(
    name: "Providence",
    links: [
    "Boston",
    "Hartford"
    ]
    );
// End Northeast, begin East

$Buffalo = new Territory(
    name: "Buffalo",
    links: [
    "Detroit",
    "Toronto",
    "Albany",
    "Cleveland"
    ]
    );

$Albany = new Territory(
    name: "Albany",
    links: [
    "Buffalo",
    "Boston",
    "New York",
    "Hartford"
    ]
    );

$Hartford = new Territory(
    name: "Hartford",
    links: [
    "Albany",
    "Boston",
    "Providence",
    "New York"
    ]
    );

$LongIsland = new Territory(
    name: "Long Island",
    links: [
    "New York"
    ]
    );

$NewYork = new Territory(
    name: "New York",
    links: [
    "Albany",
    "Hartford",
    "Long Island",
    "Philadelphia"
    ]
    );

$Pittsburgh = new Territory(
    name: "Pittsburgh",
    links: [
    "Cleveland",
    "Columbus",
    "Philadelphia",
    "Washington"
    ]
    );

$Philadelphia = new Territory(
    name: "Philadelphia",
    links: [
    "Pittsburgh",
    "New York",
    "Washington"
    ]
    );

$Washington = new Territory(
    name: "Washington",
    links: [
    "Pittsburgh",
    "Philadelphia",
    "Richmond",
    "Norfolk"
    ]
    );

$Richmond = new Territory(
    name: "Richmond",
    links: [
    "Washington",
    "Norfolk",
    "Charlotte"
    ]
    );

$Norfolk = new Territory(
    name: "Norfolk",
    links: [
    "Washington",
    "Richmond"
    ]
    );
// end territory list

?>