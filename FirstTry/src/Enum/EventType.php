<?php

namespace App\Enum;

enum EventType:string
{
    case BOARDGAMES_DEMO = "Boardgames_Demo";
    case ROLE_PLAY = "Role_Play";
    case FESTIVAL = "Festival";
    case GAMING_SALES = "Gaming_Sales";
    case TOURNAMENT = "Tournament";
    
}