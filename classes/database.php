<?php
class database{

  function opencon(): PDO{
    return new PDO(
      'mysql:host=localhost; dbname=rebualibrarymanagement',
      username: 'root',
      password: '');

  }
  function insertUser($email, $password_hash, $is_active){
    $con = $this->opencon();

    try{
    $con->beginTransaction();
    $stmt = $con->prepare('INSERT INTO users(username,user_password_hash,is_active) VALUES(?,?,?)');
    $stmt->execute([$email, $password_hash, $is_active]);
    $user_id = $con->lastInsertId();
    $con->commit();
    return $user_id;

    }catch(PDOException $e){
     if($con->inTransaction()){
      $con->rollBack();
     }

  }

} 
function insertBorrowers($firstname,$lastname,$email,$phone,$member_since,$is_active){
  $con = $this->opencon();

  try{
  $con->beginTransaction();
  $stmt = $con->prepare('INSERT INTO borrowers(borrower_firstname,borrower_lastname,borrower_email, borrower_phone_number,borrower_member_since, is_active) VALUES(?,?,?,?,?,?)');
  $stmt->execute([$firstname,$lastname,$email, $phone,$member_since,$is_active]);
  $borrower_id = $con->lastInsertId();
  $con->commit();
  return $borrower_id;

  }catch(PDOException $e){
    if($con->inTransaction()){
      $con->rollBack();
    }
  }
}

    function insertBorroweruser($user_id,$borrower_id){
      $con = $this->opencon();

      try{
      $con->beginTransaction();
      $stmt = $con->prepare('INSERT INTO borroweruser(user_id,borrower_id) VALUES(?,?)');
      $stmt->execute([$user_id, $borrower_id]);
      $bu_id = $con->lastInsertId();
      $con->commit();
      return true;

      }catch(PDOException $e){
       if($con->inTransaction()){
        $con->rollBack();
       }

    }

  }

  function viewBorroweruser(){
    $con = $this->opencon();
    return $con->query("SELECT * from Borrowers")->fetchAll();
  }

function insertBorroweraddress($borrower_id,$ba_house_number,$ba_street,$ba_barangay,$ba_city,$ba_province,$ba_postal_code,$is_primary){
  $con = $this->opencon();

  try{
    $con->beginTransaction();
    $stmt = $con->prepare('INSERT INTO borroweraddress(borrower_id,ba_house_number,ba_street,ba_barangay,ba_city,ba_province,ba_postal_code,is_primary) VALUES(?,?,?,?,?,?,?,?)');
      $stmt->execute([$borrower_id,$ba_house_number,$ba_street,$ba_barangay,$ba_city,$ba_province,$ba_postal_code,$is_primary]);
      $ba_id = $con->lastInsertId();
      $con->commit();
      return true;

  } catch(PDOException $e){
    if($con->inTransaction()){
      $con->rollBack();
  }
}
}

function insertBooks($book_title,$book_isbn,$book_publication_year,$book_edition,$book_publisher){
  $con = $this->opencon();

  try{
    $con->beginTransaction();
    $stmt = $con->prepare('INSERT INTO books(book_title,book_isbn,book_publication_year,book_edition,book_publisher) VALUES(?,?,?,?,?)');
    $stmt->execute([$book_title,$book_isbn,$book_publication_year,$book_edition,$book_publisher]);
    $book_id = $con->lastInsertId();
    $con->commit();
    return true;

  }catch(PDOException $e){
    if($con->inTransaction()){
      $con->rollBack();
    }
  }
}
function viewBooks(){
  $con = $this->opencon();
  return $con->query("SELECT * FROM books")->fetchAll();
}

function insertBookcopy($book_id,$status){
  $con = $this->opencon();

  try{
    $con->beginTransaction();
    $stmt = $con->prepare('INSERT INTO bookcopy(book_id,status) VALUES(?,?)');
    $stmt->execute([$book_id,$status]);
    $copy_id = $con->lastInsertId();
    $con->commit();
    return true;

  }catch(PDOException $e){
    if($con->inTransaction()){
      $con->rollBack();
    }
  }
}

function viewAuthors(){
  $con = $this->opencon();
  return $con->query("SELECT * FROM authors")->fetchAll();
}
function insertBookauthors($book_id,$author_id){
  $con = $this->opencon();

  try{
    $con->beginTransaction();
    $stmt = $con->prepare('INSERT INTO bookauthors(book_id,author_id) VALUES(?,?)');
    $stmt->execute([$book_id,$author_id]);
    $ba_id = $con->lastInsertId();
    $con->commit();
    return true;

  }catch(PDOException $e){
    if($con->inTransaction()){
      $con->rollBack();
    }
  }
}

function viewGenre(){
  $con = $this->opencon();
  return $con->query("SELECT * FROM genre")->fetchAll();
}

function insertBookGenre($genre_id,$book_id){
  $con = $this->opencon();

  try{
    $con->beginTransaction();
    $stmt = $con->prepare('INSERT INTO bookgenre(genre_id,book_id) VALUES(?,?)');
    $stmt->execute([$genre_id,$book_id]);
    $gb_id = $con->lastInsertId();
    $con->commit();
    return true;

  }catch(PDOException $e){
    if($con->inTransaction()){
      $con->rollBack();
    }
  }
}

function viewCopies(){
$con = $this->opencon();
return $con->query("SELECT books.book_id,
books.book_title, books.book_isbn, books.book_publication_year, COUNT(bookcopy.copy_id) AS Copies,
SUM(bookcopy.status = 'Available') AS Available_copies
FROM books
JOIN bookcopy ON bookcopy.book_id = books.book_id
GROUP BY 1;")->fetchAll();

}

}
?>