<?php
require_once('../classes/database.php');

$con = new database();
$allbooks = $con->viewBooks();
$allauthors = $con->viewAuthors();
$allgenre = $con->viewGenre();

$booksCreateStatus = null;
$booksCreateMessage = ' ';

if(isset($_POST['save_book'])){
$book_title = $_POST['book_title'];
$book_isbn = $_POST['book_isbn'];
$book_publication_year = $_POST['book_publication_year'];
$book_edition = $_POST['book_edition'];
$book_publisher = $_POST['book_publisher'];

try{

  $book_id = $con->insertBooks($book_title,$book_isbn,$book_publication_year,$book_edition,$book_publisher);

$booksCreateStatus = 'success';
$booksCreateMessage = 'Books added successfully';

} catch(Exception $e){

$booksCreateStatus = 'error';
$booksCreateMessage= $e->getMessage();
}
}
$copyCreateStatus = null;
$copyCreateMessage = ' ';

if(isset($_POST['add_copy'])){
  $book_id = $_POST['book_id'];
  $status = $_POST['status'];

  try{

    $copy_id = $con->insertBookCopy($book_id,$status);

    $copyCreateStatus = 'success';
    $copyCreateMessage = 'Book copy added successfully';

  } catch(Exception $e){

    $copyCreateStatus = 'error';
    $copyCreateMessage = $e->getMessage();
  }
}

if(isset($_POST['assign_author'])){
  $book_id= $_POST['book_id'];
  $author_id = $_POST['author_id'];
  
  try{
  
    $ba_id = $con->insertBookauthors($book_id,$author_id);
  
  $authorCreateStatus = 'success';
  $authorCreateMessage = 'Book Author added successfully';
  
  } catch(Exception $e){
  
  $authorCreateStatus = 'error';
  $authorCreateMessage=  $e->getMessage();
  } 
}

if(isset($_POST['assign_genre'])){
  $genre_id = $_POST['genre_id'];
  $book_id= $_POST['book_id'];
  
  try{
  
    $gb_id = $con->insertBookGenre($genre_id,$book_id);
  
  $genreCreateStatus = 'success';
  $genreCreateMessage = 'Book Genre added successfully';
  
  } catch(Exception $e){
  
  $genreCreateStatus = 'error';
  $genreCreateMessage=  $e->getMessage();
  }
  
}
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Books — Admin</title>
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css"> 
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.html">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBooks">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navBooks" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="admin-dashboard.html">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="books.html">Books</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.html">Borrowers</a></li>
        <li class="nav-item"><a class="nav-link" href="checkout.html">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="return.html">Return</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.html">Catalog</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft">Role: ADMIN</span>
        <a class="btn btn-sm btn-outline-secondary" href="login.html">Logout</a>
      </div>
    </div>
  </div>
</nav>

<main class="container py-4">
  <div class="row g-3">
    <div class="col-12 col-lg-4">
      <div class="card p-4">
        <h5 class="mb-1">Add Book</h5>
        <p class="small-muted mb-3">Creates a row in <b>Books</b>.</p>

        <!-- Later in PHP: action="../php/books/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input class="form-control" name="book_title" required>
          </div>
          <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input class="form-control" name="book_isbn" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Publication Year</label>
            <input class="form-control" name="book_publication_year" type="number" min="1500" max="2100" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Edition</label>
            <input class="form-control" name="book_edition" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Publisher</label>
            <input class="form-control" name="book_publisher" placeholder="optional">
          </div>
          <button name="save_book" button class="btn btn-primary w-100" type="submit">Save Book</button>
        </form>
      </div>

      <div class="card p-4 mt-3">
        <h6 class="mb-2">Add Copy</h6>
        <p class="small-muted mb-3">Creates a row in <b>BookCopy</b>.</p>
        <!-- Later in PHP: action="../php/copies/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Book</label>
            <select class="form-select" name="book_id" required>
              <option value="">Select book</option>
               <?php
foreach($allbooks as $books){
  echo '<option value="'.$books['book_id'].'">'.$books['book_title'].'</option>';
}
?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" required>
              <option value="AVAILABLE">AVAILABLE</option>
              <option value="ON_LOAN">ON_LOAN</option>
              <option value="LOST">LOST</option>
              <option value="DAMAGED">DAMAGED</option>
              <option value="REPAIR">REPAIR</option>
            </select>
          </div>
          <button name="add_copy" button class="btn btn-outline-primary w-100" type="submit">Add Copy</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-end mb-3">
          <div>
            <h5 class="mb-1">Books List</h5>
            <div class="small-muted">Placeholder rows. Replace with PHP + MySQL output.</div>
          </div>
          <div class="d-flex gap-2">
            <input class="form-control" style="max-width: 260px;" placeholder="Search title / ISBN...">
            <button class="btn btn-outline-secondary">Search</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>ISBN</th>
                <th>Year</th>
                <th>Publisher</th>
                <th>Copies</th>
                <th>Available</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
             <?php
             
             $viewcopies = $con->viewCopies();
             foreach($viewcopies as $vc){


              echo'<tr>';
                echo '<td>'.$vc['book_id'].'</td>';
                echo '<td>'.$vc['book_title'].'</td>';
                echo '<td>'.$vc['book_isbn'].'</td>';
                echo '<td>'.$vc['book_publication_year'].'</td>';
                echo'<td>'.$vc['Copies'].'</td>';
                echo'<td>'.$vc['Available_copies'].'</td>';
                echo '<td class="text-end">';
                 echo' <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBookModal">Edit</button>';
                 echo'<button class="btn btn-sm btn-outline-danger">Delete</button>';
                echo ' </td>';
              echo'</tr>';
             }
              ?>

            </tbody>
          </table>
        </div>

        <hr class="my-4">

        <div class="row g-3">
          <div class="col-12 col-lg-6">
            <div class="border rounded p-3">
              <h6 class="mb-2">Assign Author to Book</h6>
              <p class="small-muted mb-3">Creates a row in <b>BookAuthors</b>.</p>
              <!-- Later in PHP: action="../php/bookauthors/create.php" method="POST" -->
              <form action="#" method="POST" class="row g-2">
                <div class="col-12 col-md-6">
                  <select class="form-select" name="book_id" required>
                    <option value="">Select book</option>
                    <?php
foreach($allbooks as $books){
  echo '<option value="'.$books['book_id'].'">'.$books['book_title'].'</option>';
}
?>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="author_id" required>
                    <option value="">Select author</option>
                    <?php
foreach($allauthors as $authors){
  echo '<option value="'.$authors['author_id'].'">'.$authors['author_firstname']. ' '. $authors['author_lastname'].'</option>';
}
?>
                  </select>
                </div>
                <div class="col-12">
                  <button name="assign_author" button class="btn btn-outline-primary w-100" type="submit">Assign</button>
                </div>
              </form>
              <div class="small-muted mt-2">Unique constraint prevents duplicate (book_id, author_id).</div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="border rounded p-3">
              <h6 class="mb-2">Assign Genre to Book</h6>
              <p class="small-muted mb-3">Creates a row in <b>BookGenre</b>.</p>
              <!-- Later in PHP: action="../php/bookgenre/create.php" method="POST" -->
              <form action="#" method="POST" class="row g-2">
                <div class="col-12 col-md-6">
                  <select class="form-select" name="book_id" required>
                    <option value="">Select book</option>
                    <?php
foreach($allbooks as $books){
  echo '<option value="'.$books['book_id'].'">'.$books['book_title'].'</option>';
}
?>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="genre_id" required>
                    <option value="">Select genre</option>
                    <?php
foreach($allgenre as $genre){
  echo '<option value="'.$genre['genre_id'].'">'.$genre['genre_name'].'</option>';
}
?>
                  </select>
                </div>
                <div class="col-12">
                  <button name="assign_genre" button class="btn btn-outline-primary w-100" type="submit">Assign</button>
                </div> 
              </form>
              <div class="small-muted mt-2">Unique constraint prevents duplicate (genre_id, book_id).</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<!-- Edit Book Modal (UI only) -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Book</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Later in PHP: load existing values -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input class="form-control" value="Noli Me Tangere">
          </div>
          <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input class="form-control" value="9789710810736">
          </div>
          <div class="mb-3">
            <label class="form-label">Publisher</label>
            <input class="form-control" value="National Book Store">
          </div>
          <button class="btn btn-primary w-100" type="button">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>

<script>

const createStatus = <?php echo json_encode($booksCreateStatus)?>;
const createMessage = <?php echo json_encode($booksCreateMessage)?>;

if(createStatus == 'success'){
  Swal.fire({
  icon: 'success',
  title: 'Sucess',
  text: createMessage,
  confirmButtonText: 'OK'
  });
} else if(createStatus == 'error'){
  Swal.fire({
  icon: 'error',
  title: 'Error',
  text: createMessage,
  confirmButtonText: 'OK'
  });
}
</script>


<script>

const createStatus1 = <?php echo json_encode($copyCreateStatus)?>;
const createMessage1 = <?php echo json_encode($copyCreateMessage)?>;

if(createStatus1 == 'success'){
  Swal.fire({
  icon: 'success',
  title: 'Sucess',
  text: createMessage1,
  confirmButtonText: 'OK'
  });
} else if(createStatus1 == 'error'){
  Swal.fire({
  icon: 'error',
  title: 'Error',
  text: createMessage1,
  confirmButtonText: 'OK'
  });
}
</script>

<script>

const createStatus2 = <?php echo json_encode($authorCreateStatus)?>;
const createMessage2 = <?php echo json_encode($authorCreateMessage)?>;

if(createStatus2 == 'success'){
  Swal.fire({
  icon: 'success',
  title: 'Sucess',
  text: createMessage2,
  confirmButtonText: 'OK'
  });
} else if(createStatus2 == 'error'){
  Swal.fire({
  icon: 'error',
  title: 'Error',
  text: createMessage2,
  confirmButtonText: 'OK'
  });
}
</script>


<script>

const createStatus3 = <?php echo json_encode($genreCreateStatus)?>;
const createMessage3 = <?php echo json_encode($genreCreateMessage)?>;

if(createStatus3 == 'success'){
  Swal.fire({
  icon: 'success',
  title: 'Sucess',
  text: createMessage3,
  confirmButtonText: 'OK'
  });
} else if(createStatus3 == 'error'){
  Swal.fire({
  icon: 'error',
  title: 'Error',
  text: createMessage3,
  confirmButtonText: 'OK'
  });
}
</script>

</body>
</html>