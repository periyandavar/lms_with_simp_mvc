<?php
/**
 * Home page
 * php version 7.3.5
 *
 * @category View
 * @package  View
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
defined('VALID_REQ') or exit('Invalid request');
use System\Core\Router;
use System\Core\Utility;
?>
<!-- header starts -->
<header>
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="cols">
                    <div class="text-container">
                        <h1>A PLACE TO <span class="morph"> </span></h1>
                        <p class="heading-para">We store the energy that fuels the imagination. we open up windows to
                            the world and inspire you to explore and achieve, and contribute to improving your quality
                            of life.</p>
                        <a class="btn-link"
                            href="<?php echo Router::getURL('registrationPage', 'get', []);?>">JOIN
                            NOW</a>
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
                <div class="cols col-3">
                    <div class="text-container">
                        <h1>Our Mission & Vision</h1>
                        <p class="text-para"><?php echo $mission; ?>
                        </p>
                        <p class="text-para italics-text"><?php echo $vision; ?>
                        </p>
                        <div class="text-author">- Admin</div>
                    </div>
                </div>
                <div class="cols col-5">
                    <div class="image-container">
                        <img
                            src="<?php echo Utility::baseURL()?>/static/img/cover/3.jpg">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- section 2 -->
    <section>
        <div class="home-container">
            <div class="row">
                <div class="cols col-9 container-heading">
                    <h1>Find the Books of your Taste<br>among the thounsand's collection </h1>
                </div>
            </div>
            <!-- Book row -->
            <div class="row">
                <?php if (isset($books)):?>
                    <?php foreach ($books as $book): ?>
                    <div class="card cols">
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
                            available
                            </p>
                            <div class="btn-container">
                                <a class="btn-link"
                                    href="/book/<?php echo $book->id?>">View
                                    Book</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
            <div class="btn-container" id="loadMore">
                <a class="btn-link" href="/books">SHOW ALL</a>
            </div>
    </section>
</article>
<!-- Article ends -->
<script>
    document.getElementById("menu-home").className += " active";
</script>