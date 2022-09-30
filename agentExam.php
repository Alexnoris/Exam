<?php

ini_set('memory_limit', '2.5G');
ini_set('display_errors', true);

function Generales($array){
    echo '<pre>', print_r($array, true), '</pre>';
}

// Enviroment
$enviroment = array(array("A", "B", 87), array("B", "A", 87), array("B", "C", 92), array("C", "B", 92),
                    array("C", "D", 142), array("D", "C", 142),array("D", "G", 85), array("D", "E", 98),
                    array("E", "D", 98), array("E", "F", 86), array("F", "E", 86), array("G", "D", 85),
                    array("G", "J", 211), array("G", "H", 90), array("G", "I", 101), array("H", "G", 90),
                    array("I", "G", 101), array("I", "M", 138), array("I", "L", 97), array("J", "G", 211),
                    array("J", "K", 99), array("K", "J", 99), array("K", "L", 80), array("K", "P", 140),
                    array("K", "N", 151), array("L", "K", 80), array("L", "I", 97), array("L", "M", 146),
                    array("N", "K", 151), array("N", "O", 71), array("O", "N", 71), array("O", "P", 75),
                    array("P", "O", 75), array("P", "K", 140), array("P", "Q", 118),array("Q", "P", 118),
                    array("Q", "R", 111), array("R", "Q", 111), array("R", "S", 70), array("S", "R", 70),
                    array("S", "T", 75), array("T", "S", 75), array("T", "M", 120),
                    array("M", "T", 120), array("M", "L", 146), array("M", "I", 138),
               );


// Historial
function perceptSequence($moves){
    $historial[] = $moves;

    return $historial;
}

// Sensors
function sensors($action){
    $vertices = array();
    $neighbours = array();
    $cost = 0;
    foreach ($action as $edge) {
        array_push($vertices, $edge[0], $edge[1]);
        $neighbours[$edge[0]][] = array("end" => $edge[1], "cost" => $edge[2]);
        $neighbours[$edge[1]][] = array("end" => $edge[0], "cost" => $edge[2]);
    }
    $vertices = array_unique($vertices);

    foreach ($vertices as $vertex) {
        $dist[$vertex] = INF;
        $previous[$vertex] = NULL;

    }
    return [$vertices, $dist, $neighbours, $previous];
}

// Action
function agentAction($percept, $begin, $end) {

    $sensor = sensors($percept);
   
    $sensor[1][$begin] = 0;
    
    $Q = $sensor[0];
    while (count($Q) > 0) {
        // TODO - Find faster way to get minimum
        $min = INF;
        foreach ($Q as $vertex){
            if ($sensor[1][$vertex] < $min) {
                $min = $sensor[1][$vertex];
                
                $u = $vertex;
            }
        }
        
        $Q = array_diff($Q, array($u));
        
        if ($sensor[1][$u] == INF or $u == $end) {
            break;
        }
        
        if (isset($sensor[2])) {
            foreach ($sensor[2][$u] as $arr) {
                $alt = $sensor[1][$u] + $arr["cost"];
                if ($alt < $sensor[1][$arr["end"]]) {
                    $sensor[1][$arr["end"]] = $alt;
                    $sensor[3][$arr["end"]] = $u;
                    $cost = $alt;
                    $historial = perceptSequence($sensor[3]);

                }
            }
        }
    }
    $path = array();
    $u = $end;
    while (isset($sensor[3][$u])) {
        array_unshift($path, $u);
        $u = $sensor[3][$u];
    }
    array_unshift($path, $u);
    Generales($historial);
    Generales("El precio del camino más corto: " . $cost);
    return $path;
}

$path = agentAction($enviroment, "D", "A");

echo "Camino más corto: ".implode(", ", $path)."\n";

