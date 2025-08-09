<?php
class Database {
    public static function connect() {
        return new mysqli('localhost', 'root', '', 'citasmedicas', '3307');
    }
}

//clave: 123456