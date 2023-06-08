<?php

/// Get the right greeting based on the time of day (from the computer)
function getTimeGreeting()
{
    $hour = date('Η');
    if ($hour < 12) {
        $dayTerm = "Morning!";
    } else {
        $dayTerm = "Evening!";
    }

    return "Good " . $dayTerm;
}


// Set form field variables as empty
$name = $expiryDate = $duration = $licensePlate = $vehicleType = $renewalPrice = "";

if (isset($_POST['clear'])) {
	
    // Clear form field variables
    $name = $expiryDate = $duration = $licensePlate = $vehicleType = $renewalPrice = "";
	
} elseif (isset($_POST['submit'])) {
		
	// Get values from the form
	$name = $_POST['name'] ?? "";
	$expiryDate = $_POST['expiryDate'] ?? "";
	$duration = $_POST['duration'] ?? "";
	$licensePlate = $_POST['licensePlate'] ?? "";
	$vehicleType = $_POST['vehicleType'] ?? "";
	$renewalPrice = $_POST['renewalPrice'] ?? "";

	$fieldsCompleted = true;
	$missingFields = [];

	// Check if any field is empty
	if (empty($name)) {
		$fieldsCompleted = false;
		$missingFields[] = "Name";
	}
	if (empty($expiryDate)) {
		$fieldsCompleted = false;
		$missingFields[] = "Expiry Date";
	}
	if (empty($duration)) {
		$fieldsCompleted = false;
		$missingFields[] = "Duration";
	}
	if (empty($licensePlate)) {
		$fieldsCompleted = false;
		$missingFields[] = "License Plate";
	}
	if (empty($vehicleType)) {
		$fieldsCompleted = false;
		$missingFields[] = "Vehicle Type";
	}
	if (empty($renewalPrice)) {
		$fieldsCompleted = false;
		$missingFields[] = "Renewal Price";
	}

	if (!$fieldsCompleted) {
		
		// Show an error message if any field is empty
		$errorMessage = '<p style="color: red;">Please complete the following fields: ' . implode(", ", $missingFields) . '</p>';
		
	} elseif (mb_strlen($licensePlate) !== 7) {
		
		// Show an error message if the license plate format is wrong
		$errorMessage = '<p style="color: red;">The registration number is wrong. The correct format is ABC1234</p>';
		
	} elseif (!preg_match('/^[0-9,]+$/', $renewalPrice)) {
		
		// Show an error if the price format is wrong
		$errorMessage = '<p style="color: red;">Please enter numbers and the comma only for the renewal price</p>';
		
	} else {
		
		// Generate the message if everything is correct
		$expiryDay = date('l, jS', strtotime($expiryDate));
		$generatedMessage = '<div class="message-box">';
		$generatedMessage .= '<p>';
		$generatedMessage .= getTimeGreeting() . " ";
		$generatedMessage .= 'On ' . $expiryDay . ', the contract for the vehicle with license plate ' . $licensePlate;
		$generatedMessage .= ' (Owner: ' . $name . ', Type: ' . $vehicleType . ') will expire. <br><br>';
		$generatedMessage .= ' Since there is a possibility that uninsured vehicles may be checked by the AADE,';
		$generatedMessage .= ' it is recommended to renew your contract before it expires, or declare it immobile to avoid any issues. <br><br>';
		$generatedMessage .= 'The renewal price for your contract is €' . $renewalPrice;
		$generatedMessage .= ' and the contract duration is for ' . $duration . ' months. <br><br>';
		$generatedMessage .= "If you have any questions or require assistance, please contact us at 2221000000 or 2221000000. <br><br>";
		$generatedMessage .= '</p>';
		$generatedMessage .= '</div>';
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css"> <!-- Link to the CSS file -->
</head>
<body>
<h2>Generate Message</h2>

<!-- User input form -->
<form name="myform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<table>
		<tr>
			<!-- Client's Name -->
			<td>
				<label for="name">Client's Full Name:</label>
				<input type="text" name="name" id="name" maxlength="60" class="input-text" value="<?php echo htmlspecialchars($name); ?>"><br>
			</td>
			<!-- Contract Expiry Date -->
			<td>
				<label for="expiryDate">Contract Expiry Date:</label>
				<input type="date" name="expiryDate" id="expiryDate" class="input-text" value="<?php echo htmlspecialchars($expiryDate); ?>"><br>
			</td>
		</tr>
		<tr>
			<!-- Contract Duration -->
			<td>
				<label for="duration">Contract Duration:</label>
				<select name="duration" id="duration">
					<option value="1" <?php if ($duration == "1") echo "selected"; ?>>1 month</option>
					<option value="45" <?php if ($duration == "45") echo "selected"; ?>>45 days</option>
					<option value="3" <?php if ($duration == "3") echo "selected"; ?>>3 months</option>
					<option value="6" <?php if ($duration == "6") echo "selected"; ?>>6 months</option>
					<option value="12" <?php if ($duration == "12") echo "selected"; ?>>1 year</option>
				</select><br>
			</td>
			<!-- License Plate -->
			<td>
				<label for="licensePlate">License Plate:</label>
				<input type="text" name="licensePlate" id="licensePlate" pattern="[\p{L}]{3}[0-9]{4}" class="input-text"
					title="The correct format is: ABC1234"
					minlength="7" maxlength="7"
					oninput="this.value = this.value.toUpperCase();"
					value="<?php echo htmlspecialchars($licensePlate); ?>"><br>
			</td>
		</tr>
		<tr>
			<!-- Vehicle Type -->
			<td>
				<label for="vehicleType">Vehicle Type:</label>
				<input type="text" name="vehicleType" id="vehicleType" class="input-text" value="<?php echo htmlspecialchars($vehicleType); ?>"><br>
			</td>
			<!-- Renewal Price -->
			<td>
				<label for="renewalPrice">Renewal Price:</label>
				<input type="text" name="renewalPrice" id="renewalPrice" pattern="[0-9,]+" class="input-text"
					title="Please only use the comma and numbers"
					value="<?php echo htmlspecialchars($renewalPrice); ?>"><br>
			</td>
		</tr>
	</table>
		
	<!-- Generate Message Button -->
	<input type="submit" name="submit" value="Generate Message" class="button">
			
	<!-- Clear Form Button -->
	<input type="submit" name="clear" value="Clear Form" class="button">
</form>

<!-- Display error message if it exists -->
<?php if (isset($errorMessage)) : ?>
	<?php echo $errorMessage; ?>
<?php endif; ?>

<!-- Display generated message -->
<?php if (isset($generatedMessage)) : ?>
	<?php echo $generatedMessage; ?>
<?php endif; ?>

</body>
</html>
