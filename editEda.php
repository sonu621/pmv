<?php
include('partials/_connection.php'); // Ensure $conn is a mysqli object
session_start();
// Check if the 'id' is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $form_id = $_GET['id'];

    // Fetch the specific record based on the id
    $sql = "SELECT * FROM form_data WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $form_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if a record is found
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "No data found for the selected form.";
        exit;
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
    exit;
}

include('partials/_header.php'); // Include header for consistency
?>

<div class="container bg-light py-4 mt-5">
    <?php include('partials/_nav.php'); // Include navbar ?>

    <div class="text-center d-flex justify-content-between align-items-center  p-2">
        <h4>Form Data Details</h4>
        <!-- Adding space between the heading and the button -->
        <div class="text-center">
            <!-- Back Button -->
            <a href="view_eda.php" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
    <hr>

    <!-- Display the form data details -->
    <div class="mb-3">
        <strong>Username:</strong> <?php echo htmlspecialchars($row['username']); ?>
    </div>
    <div class="mb-3">
        <strong>Recipient Username:</strong> <?php echo htmlspecialchars($row['recipient_username']); ?>
    </div>
    <div class="mb-3">
        <strong>Vanity URL:</strong> <?php echo htmlspecialchars($row['vanity_url']); ?>
    </div>
    <div class="mb-3">
        <strong>Amount:</strong> $<?php echo htmlspecialchars($row['amount']); ?>
    </div>
    <div class="mb-3">
        <strong>Server Username:</strong> <?php echo htmlspecialchars($row['server_username']); ?>
    </div>
    <div class="mb-3">
        <strong>Text Area:</strong> <?php echo nl2br(htmlspecialchars($row['text_area'])); ?>
    </div>
    <div class="mb-3">
        <strong>File:</strong> <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View
            File</a>
    </div>

    <!-- Buttons for PDF, Excel, and Print -->
    <div class="mt-4">
        <button id="downloadPdf" class="btn btn-success">Download PDF</button>
        <button id="downloadExcel" class="btn btn-primary">Download Excel</button>
        <button id="printButton" class="btn btn-warning">Print</button>
    </div>

</div>

<?php include('partials/_footer.php'); // Include footer ?>

<!-- Add JavaScript for PDF and Excel generation and print functionality -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

<script>
// PDF Generation
document.getElementById("downloadPdf").addEventListener("click", function() {
    const {
        jsPDF
    } = window.jspdf;
    const doc = new jsPDF();

    doc.text("Form Data Details", 10, 10);
    doc.text("Username: <?php echo htmlspecialchars($row['username']); ?>", 10, 20);
    doc.text("Recipient Username: <?php echo htmlspecialchars($row['recipient_username']); ?>", 10, 30);
    doc.text("Vanity URL: <?php echo htmlspecialchars($row['vanity_url']); ?>", 10, 40);
    doc.text("Amount: $<?php echo htmlspecialchars($row['amount']); ?>", 10, 50);
    doc.text("Server Username: <?php echo htmlspecialchars($row['server_username']); ?>", 10, 60);
    doc.text("Text Area: <?php echo nl2br(htmlspecialchars($row['text_area'])); ?>", 10, 70);

    // For file path, you may need to handle it differently
    doc.text("File Path: <?php echo htmlspecialchars($row['file_path']); ?>", 10, 80);

    // Download the generated PDF
    doc.save("form_data_details.pdf");
});

// Excel Generation
document.getElementById("downloadExcel").addEventListener("click", function() {
    var wb = XLSX.utils.book_new();
    var ws_data = [
        ["Username", "Recipient Username", "Vanity URL", "Amount", "Server Username", "Text Area",
            "File Path"
        ],
        ["<?php echo htmlspecialchars($row['username']); ?>",
            "<?php echo htmlspecialchars($row['recipient_username']); ?>",
            "<?php echo htmlspecialchars($row['vanity_url']); ?>",
            "$<?php echo htmlspecialchars($row['amount']); ?>",
            "<?php echo htmlspecialchars($row['server_username']); ?>",
            "<?php echo nl2br(htmlspecialchars($row['text_area'])); ?>",
            "<?php echo htmlspecialchars($row['file_path']); ?>"
        ]
    ];
    var ws = XLSX.utils.aoa_to_sheet(ws_data);
    XLSX.utils.book_append_sheet(wb, ws, "Form Data");

    // Save the Excel file
    XLSX.writeFile(wb, "form_data_details.xlsx");
});

// Print Function
document.getElementById("printButton").addEventListener("click", function() {
    var content = `
            <div style="font-family: Arial, sans-serif; padding: 20px;">
                <h2>Form Data Details</h2>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($row['username']); ?></p>
                <p><strong>Recipient Username:</strong> <?php echo htmlspecialchars($row['recipient_username']); ?></p>
                <p><strong>Vanity URL:</strong> <?php echo htmlspecialchars($row['vanity_url']); ?></p>
                <p><strong>Amount:</strong> $<?php echo htmlspecialchars($row['amount']); ?></p>
                <p><strong>Server Username:</strong> <?php echo htmlspecialchars($row['server_username']); ?></p>
                <p><strong>Text Area:</strong> <?php echo nl2br(htmlspecialchars($row['text_area'])); ?></p>
                <p><strong>File:</strong> <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View File</a></p>
            </div>
        `;

    // Open print window
    var printWindow = window.open("", "", "width=600,height=600");
    printWindow.document.write(content);
    printWindow.document.close();
    printWindow.print();
});
</script>