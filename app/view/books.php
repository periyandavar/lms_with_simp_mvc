
<?php
/**
 * Books
 * php version 7.3.5
 *
 * @category View
 * @package  View
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
use System\Core\Utility;
if (!isset($books)) {
    return;
}
?>
<!-- header starts -->
<header class="books-header">
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="cols">
                    <div class="text-container">
                        <h1>A PLACE TO <span class="morph"> </span></h1>
                        <p class="heading-para">Find the Books of your Taste.. <br>Among the thounsand's collection.</p>
                        <a class="btn-link" href="/signup">JOIN NOW</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header ends -->
<!-- main content article start -->
<article>
    <!-- section 1 -->
    <section>
        <div class="home-container">
            <div class="row">
                <div class="cols col-9 container-heading">
                    <h1>Find the Books of your Taste<br>among the thounsand's collection </h1>
                </div>
            </div>
            <!-- Book row -->
            <div class="row" id="books-list">
                <?php if (isset($books)):?>
                    <?php foreach ($books as $book): ?>
                        <div class="card cols">
                        <!-- <div class="card-image img-container"> -->
                        <!-- <div class="img-container"> -->
                        <!-- <img src="/upload/books/" alt="alternative">
                                    <div class="overlay">
                                        <div class="details">"" <br><br> by </div>
                                    </div> -->
                        <!-- </div></div> -->
                        <a href="/book/<?php echo $book->id; ?>">

                        <book-element
                            cover="<?php echo Utility::baseURL()?>/upload/book/<?php echo $book->coverPic;?>"
                            book="<?php echo $book->name; ?>"
                            author="<?php echo $book->authors;?>"
                            id="<?php echo $book->id;?>">
                        </book-element>
                        </a>
                        <div class="card-content">
                            <h3><?php echo $book->name?>
                            </h3>
                            <div class="text-author"><?php echo $book->authors;?>
                            </div>
                            <p><?php echo $book->description;?>
                            </p>
                            <p>
                            <?php echo ($book->available == 0) ? "no copy" 
                                : ("only " . $book->available
                                . (($book->available == 1) ? " copy" : " copies"));
                            ?>
                            available</p>
                            <div class="btn-container">
                                <div class="btn-container">
                                    <a class="btn-link"
                                        href="/book/<?php echo $book->id?>">View
                                        Book</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
            <!-- show more button -->
            <div class="btn-container" id="loadMore">
                <a class="btn-link" onclick="loadMoreBooks(event);">SHOW MORE</a>
            </div>
    </section>
</article>
<script>
    document.getElementById("menu-books").className += " active";
</script>