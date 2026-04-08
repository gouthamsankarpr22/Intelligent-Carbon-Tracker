<?php
function getTimeFactor($sub_activity, $quantity){

    /* 🌬 COOLING */
    if (stripos($sub_activity, 'Air Conditioner') !== false)
        return $quantity > 6 ? 1.6 : ($quantity > 3 ? 1.3 : 1);

    if (stripos($sub_activity, 'Cooler') !== false)
        return $quantity > 6 ? 1.3 : 1.1;

    if (stripos($sub_activity, 'Fan') !== false)
        return $quantity > 8 ? 1.3 : ($quantity > 4 ? 1.2 : 1.1);


    /* 💡 LIGHTING */
    if (stripos($sub_activity, 'LED') !== false)
        return $quantity > 6 ? 1.2 : 1;

    if (stripos($sub_activity, 'Tube Light') !== false)
        return $quantity > 6 ? 1.2 : 1;

    if (stripos($sub_activity, 'Bulb') !== false)
        return $quantity > 6 ? 1.3 : 1.1;


    /* 📺 ELECTRONICS */
    if (stripos($sub_activity, 'TV') !== false)
        return $quantity > 5 ? 1.2 : 1;

    if (stripos($sub_activity, 'Laptop') !== false)
        return $quantity > 6 ? 1.2 : 1;

    if (stripos($sub_activity, 'Computer') !== false)
        return $quantity > 6 ? 1.3 : 1;

    if (stripos($sub_activity, 'Mobile') !== false)
        return $quantity > 4 ? 1.1 : 1;


    /* 🍳 KITCHEN */
    if (stripos($sub_activity, 'Refrigerator') !== false)
        return 1.2; // always running

    if (stripos($sub_activity, 'Microwave') !== false)
        return $quantity > 1 ? 1.2 : 1;

    if (stripos($sub_activity, 'Induction Stove') !== false)
        return $quantity > 2 ? 1.3 : 1.1;

    if (stripos($sub_activity, 'Electric Kettle') !== false)
        return $quantity > 2 ? 1.2 : 1;


    /* 🧺 HOUSEHOLD */
    if (stripos($sub_activity, 'Washing Machine') !== false)
        return $quantity > 2 ? 1.3 : 1;

    if (stripos($sub_activity, 'Iron') !== false)
        return $quantity > 2 ? 1.2 : 1;

    if (stripos($sub_activity, 'Vacuum Cleaner') !== false)
        return $quantity > 2 ? 1.3 : 1;


    /* 🚿 HEATING */
    if (stripos($sub_activity, 'Geyser') !== false)
        return $quantity > 2 ? 1.4 : 1.2;

    if (stripos($sub_activity, 'Heater') !== false)
        return $quantity > 3 ? 1.5 : 1.2;


    /* 🚗 TRANSPORT */
    if (stripos($sub_activity, 'Car') !== false)
        return $quantity > 50 ? 1.4 : ($quantity > 20 ? 1.2 : 1);

    if (stripos($sub_activity, 'Bike') !== false)
        return $quantity > 40 ? 1.3 : 1.1;

    if (stripos($sub_activity, 'Bus') !== false)
        return $quantity > 20 ? 1.2 : 1;

    if (stripos($sub_activity, 'Train') !== false)
        return $quantity > 50 ? 1.1 : 1;


    return 1;
}

function getDynamicEmission($sub_activity, $base, $quantity){

    /* 🚗 VEHICLES */
    if (stripos($sub_activity, 'Electric Car') !== false) return $base * 0.6;
    if (stripos($sub_activity, 'Petrol Car') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Diesel Car') !== false) return $base * 1.3;

    if (stripos($sub_activity, 'Electric Bike') !== false) return $base * 0.5;
    if (stripos($sub_activity, 'Petrol Bike') !== false) return $base * 1.1;

    if (stripos($sub_activity, 'Bus') !== false) return $base * 0.9;
    if (stripos($sub_activity, 'Train') !== false) return $base * 0.7;


    /* 🌬 COOLING */
    if (stripos($sub_activity, 'Air Conditioner') !== false) return $base * 1.5;
    if (stripos($sub_activity, 'Cooler') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Fan') !== false) return $base * 0.9;


    /* 💡 LIGHTING */
    if (stripos($sub_activity, 'LED') !== false) return $base * 0.6;
    if (stripos($sub_activity, 'Tube Light') !== false) return $base * 0.8;
    if (stripos($sub_activity, 'Bulb') !== false) return $base * 1.2;


    /* 📺 ELECTRONICS */
    if (stripos($sub_activity, 'TV') !== false) return $base * 1.1;
    if (stripos($sub_activity, 'Laptop') !== false) return $base * 0.9;
    if (stripos($sub_activity, 'Computer') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Mobile') !== false) return $base * 0.7;


    /* 🍳 KITCHEN */
    if (stripos($sub_activity, 'Refrigerator') !== false) return $base * 1.3;
    if (stripos($sub_activity, 'Microwave') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Induction Stove') !== false) return $base * 1.1;
    if (stripos($sub_activity, 'Electric Kettle') !== false) return $base * 1.1;


    /* 🧺 HOUSEHOLD */
    if (stripos($sub_activity, 'Washing Machine') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Iron') !== false) return $base * 1.2;
    if (stripos($sub_activity, 'Vacuum Cleaner') !== false) return $base * 1.3;


    /* 🚿 HEATING */
    if (stripos($sub_activity, 'Geyser') !== false) return $base * 1.4;
    if (stripos($sub_activity, 'Heater') !== false) return $base * 1.5;


    return $base;
}

function getEfficiencyFactor($sub_activity){

    /* 🌱 HIGH EFFICIENCY (LOW EMISSION) */
    if (stripos($sub_activity, 'LED') !== false) return 0.6;
    if (stripos($sub_activity, '5 Star') !== false) return 0.7;
    if (stripos($sub_activity, 'Inverter AC') !== false) return 0.75;
    if (stripos($sub_activity, 'Electric Vehicle') !== false) return 0.6;
    if (stripos($sub_activity, 'Electric Car') !== false) return 0.6;
    if (stripos($sub_activity, 'Electric Bike') !== false) return 0.6;


    /* ⚖ MEDIUM EFFICIENCY */
    if (stripos($sub_activity, 'Fan') !== false) return 0.9;
    if (stripos($sub_activity, 'Tube Light') !== false) return 0.85;
    if (stripos($sub_activity, 'Laptop') !== false) return 0.9;
    if (stripos($sub_activity, 'Mobile') !== false) return 0.85;
    if (stripos($sub_activity, 'Induction Stove') !== false) return 0.9;


    /* ⚡ NORMAL */
    if (stripos($sub_activity, 'TV') !== false) return 1;
    if (stripos($sub_activity, 'Refrigerator') !== false) return 1;
    if (stripos($sub_activity, 'Washing Machine') !== false) return 1;


    /* 🔥 LOW EFFICIENCY (HIGH CONSUMPTION) */
    if (stripos($sub_activity, 'Air Conditioner') !== false) return 1.2;
    if (stripos($sub_activity, 'Heater') !== false) return 1.4;
    if (stripos($sub_activity, 'Geyser') !== false) return 1.3;
    if (stripos($sub_activity, 'Iron') !== false) return 1.2;
    if (stripos($sub_activity, 'Microwave') !== false) return 1.1;
    if (stripos($sub_activity, 'Vacuum Cleaner') !== false) return 1.3;


    /* 🚗 VEHICLES */
    if (stripos($sub_activity, 'Petrol Car') !== false) return 1.3;
    if (stripos($sub_activity, 'Diesel Car') !== false) return 1.4;
    if (stripos($sub_activity, 'Petrol Bike') !== false) return 1.2;


    return 1;
}

function getSeasonalFactor($sub_activity, $month){

    /* 🌞 SUMMER (Mar–Jun) */
    if ($month >= 3 && $month <= 6) {
        if (stripos($sub_activity, 'Air Conditioner') !== false) return 1.5;
        if (stripos($sub_activity, 'Cooler') !== false) return 1.3;
        if (stripos($sub_activity, 'Fan') !== false) return 1.2;
        if (stripos($sub_activity, 'Refrigerator') !== false) return 1.1;
    }

    /* 🌧 MONSOON (Jul–Sep) */
    if ($month >= 7 && $month <= 9) {
        if (stripos($sub_activity, 'Fan') !== false) return 1.1;
        if (stripos($sub_activity, 'Dryer') !== false) return 1.3;
        if (stripos($sub_activity, 'Washing Machine') !== false) return 1.2;
    }

    /* ❄ WINTER (Oct–Feb) */
    if ($month >= 10 || $month <= 2) {
        if (stripos($sub_activity, 'Heater') !== false) return 1.5;
        if (stripos($sub_activity, 'Geyser') !== false) return 1.4;
        if (stripos($sub_activity, 'Iron') !== false) return 1.2;
    }

    /* ⚡ ALWAYS SLIGHT VARIATION */
    if (stripos($sub_activity, 'TV') !== false) return 1.05;
    if (stripos($sub_activity, 'Laptop') !== false) return 1.05;

    return 1;
}

function updateLearningFactor($user_id, $sub_activity, $conn){

    // Get avg usage
    $stmt = $conn->prepare("
        SELECT AVG(d.quantity) as avg_qty
        FROM daily_log d
        JOIN activities a ON d.activity_id = a.activity_id
        WHERE d.user_id=? AND a.sub_activity=?
    ");
    $stmt->execute([$user_id, $sub_activity]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $avg = $data['avg_qty'] ?? 0;

    // Decide factor
    if ($avg < 2) $factor = 0.9;
    elseif ($avg < 5) $factor = 1;
    elseif ($avg < 8) $factor = 1.1;
    else $factor = 1.2;

    // ✅ INSERT OR UPDATE (NO DUPLICATES)
    $stmt = $conn->prepare("
        INSERT INTO emission_learning (user_id, sub_activity, avg_quantity, adjusted_factor, last_updated)
        VALUES (?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
            avg_quantity = VALUES(avg_quantity),
            adjusted_factor = VALUES(adjusted_factor),
            last_updated = NOW()
    ");

    $stmt->execute([$user_id, $sub_activity, $avg, $factor]);
}

function getLearningFactor($user_id, $sub_activity, $conn){

    $stmt = $conn->prepare("
        SELECT adjusted_factor 
        FROM emission_learning 
        WHERE user_id=? AND sub_activity=?
    ");
    $stmt->execute([$user_id, $sub_activity]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['adjusted_factor'] ?? 1;
}


?>