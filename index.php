<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
   <title>Document</title>
</head>

<style>
   .middle {
      width: 600px;
   }

   .exit {
      font-size: 12px;
   }
</style>

<body class="bg-secondary">
   <div class="container middle mt-5" align="center">
      <div class="card shadow">
         <div class="card-header">
            <div class="container">
               <div class="h2">XML Creation</div>
            </div>
         </div>
         <div class="card-body">
            <form method="POST">
               <div class="card-text">
                  <div class="h3 mb-3">Click the Button Below to View the XML</div>
                  <?php if (isset($_GET['success'])) { ?>
                     <div class="container">
                        <div class="alert alert-primary" role="alert">
                           <i class="bi bi-info-circle"><span>&nbsp;<?php echo $_GET['success']; ?> </span>
                              <button type="button" class="btn-close exit" data-bs-dismiss="alert" aria-label="Close"></button>
                              <h5>XML File generated Sucessfully. <br />Click <a href='books.xml'> Created XML File </a> Link to open </h5>
                           </i>
                        </div>
                     </div>
                  <?php } ?>
                  <?php if (isset($_GET['error'])) { ?>
                     <div class="container">
                        <div class="alert alert-primary" role="alert">
                           <i class="bi bi-info-circle"><span>&nbsp;<?php echo $_GET['error']; ?></span>
                              <button type="button" class="btn-close exit" data-bs-dismiss="alert" aria-label="Close"></button>
                              <h5> Click Generate Now</h5>
                           </i>
                        </div>
                     </div>
                  <?php } ?>
                  <input type="submit" name="create" value="Input to Database" class="btn btn-danger mb-3">
                  <input type="submit" name="generate" value="Generate XML File" class="btn btn-primary mb-3">
               </div>
            </form>
         </div>
      </div>



   </div>



   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php

/** create XML file */
$mysqli = new mysqli("localhost", "root", "", "test_php_dbbookstore");

/* check connection */
if ($mysqli->connect_errno) {

   echo "Connect failed " . $mysqli->connect_error;

   exit();
}



if (isset($_POST["create"])) {

   $affectedRow = 0;

   $xml = simplexml_load_file("book.xml") or die("Error: Cannot create object");

   foreach ($xml->children() as $row) {
      $title = $row->title;
      $author = $row->author;
      $price = $row->price;
      $ISBN = $row->ISBN;
      $category = $row->category;

      $sql = "INSERT INTO books(title,author,price,ISBN,category) VALUES ('" . $title . "','" . $author . "','" . $price . "','" . $ISBN . "','" . $category . "')";

      $result = mysqli_query($mysqli, $sql);

      if (!empty($result && $affectedRow > 0)) {

         $affectedRow++;

      }

   }

   header("location: index.php?error=Xml Insert Successfully");
   exit;
}



if (isset($_POST["generate"])) {

   $query = "SELECT id, title, author, price, ISBN, category FROM books";

   $booksArray = array();

   if ($result = $mysqli->query($query)) {

      /* fetch associative array */
      while ($row = $result->fetch_assoc()) {

         array_push($booksArray, $row);
      }

      if (count($booksArray)) {

         $xmlfile = createXMLfile($booksArray);

         header("location: index.php?success=Xml Created-Successfully");
         exit;
      }

      /* free result set */
      $result->free();
   }
}



/* close connection */
$mysqli->close();

function createXMLfile($booksArray)
{

   $filePath = 'books.xml';

   $dom     = new DOMDocument('1.0', 'utf-8');

   $root      = $dom->createElement('books');

   for ($i = 0; $i < count($booksArray); $i++) {

      $bookId        =  $booksArray[$i]['id'];

      $bookName      =  htmlspecialchars($booksArray[$i]['title']);

      $bookAuthor    =  $booksArray[$i]['author'];

      $bookPrice     =  $booksArray[$i]['price'];

      $bookISBN      =  $booksArray[$i]['ISBN'];

      $bookCategory  =  $booksArray[$i]['category'];

      $book = $dom->createElement('book');

      $book->setAttribute('id', $bookId);

      $name     = $dom->createElement('title', $bookName);

      $book->appendChild($name);

      $author   = $dom->createElement('author', $bookAuthor);

      $book->appendChild($author);

      $price    = $dom->createElement('price', $bookPrice);

      $book->appendChild($price);

      $isbn     = $dom->createElement('ISBN', $bookISBN);

      $book->appendChild($isbn);

      $category = $dom->createElement('category', $bookCategory);

      $book->appendChild($category);

      $root->appendChild($book);
   }

   $dom->appendChild($root);

   $dom->save($filePath);
}
