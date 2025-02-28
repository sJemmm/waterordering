<?php
session_start();
include('dwos.php'); // Database connection

// Check if user data is present in session
if (!isset($_SESSION['user_data'])) {
    header('Location: stationregister.php');
    exit();
}

// Fetch available membership plans from the database
$plans_query = "SELECT * FROM memberships";
$plans_result = mysqli_query($conn, $plans_query);

// Handle form submission
if (isset($_POST['avail'])) {
    $selected_plan_id = $_POST['plan']; // Get selected plan ID from form

    // Get the plan details from the database based on the selected plan ID
    $plan_query = "SELECT * FROM memberships WHERE membership_id = $selected_plan_id";
    $plan_result = mysqli_query($conn, $plan_query);
    
    if (mysqli_num_rows($plan_result) > 0) {
        $plan = mysqli_fetch_assoc($plan_result);

        // Store selected plan in session for later use
        $_SESSION['user_data']['plan'] = $plan;

        // Redirect to the payment page
        header('Location: payment.php');
        exit();
    } else {
        echo "Selected plan not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Choose a Subscription Plan</title>
</head>
<body>
    <div class="subscription-container">
        <h3>Select a Premium Membership Plan</h3>
        <form action="" method="post">
            <?php while ($plan = mysqli_fetch_assoc($plans_result)) { ?>
                <div class="plan">
                    <input type="radio" id="plan_<?php echo $plan['membership_id']; ?>" name="plan" value="<?php echo $plan['membership_id']; ?>" required>
                    <label for="plan_<?php echo $plan['membership_id']; ?>">
                        Buy premium for <?php echo $plan['duration_in_days']; ?> days for only ₱<?php echo $plan['price']; ?>
                    </label>
                </div>
            <?php } ?>
            <div class="button-container">
                <input type="submit" name="avail" value="Avail" class="form-btn">
            </div>
        </form>
    </div>
</body>
</html>
