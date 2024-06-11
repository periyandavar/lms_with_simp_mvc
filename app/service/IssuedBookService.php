<?php
/**
 * IssuedBookService File Doc Comment
 * php version 7.3.5
 *
 * @category Service
 * @package  Service
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace App\Service;

defined('VALID_REQ') or exit('Invalid request');
use System\Core\BaseService;

defined('VALID_REQ') or exit('Invalid request');
/**
 * IssuedBookService Class Handles the IssuedBookService class logical operations
 *
 * @category   Service
 * @package    Service
 * @subpackage IssuedBookService
 * @author     Periyandavar <periyandavar@gmail.com>
 * @license    http://license.com license
 * @link       http://url.com
 */

class IssuedBookService extends BaseService
{
    /**
     * Checks the user eligible to lend a new book
     *
     * @param object $user        User
     * @param int    $maxLendBook Maximum number of books can user lend
     *
     * @return bool
     */
    public function checkUserCondition(object $user, int $maxLendBook): bool
    {
        return ($user->lent < $maxLendBook);
    }

    /**
     * Checks whether the user and book are satisfying the lend condition
     *
     * @param object $user        User
     * @param object $book        Book
     * @param int    $maxLendBook Maximum number of books the user can lend
     * @param string $msg         String Reference variable where the reason stord
     *                            if the condition is false
     *
     * @return boolean
     */
    public function checkLendCondition(
        object $user,
        object $book,
        int $maxLendBook,
        string &$msg = null
    ): bool {
        if ($user->id == null) {
            $msg = "The user account is disabled or not exists";
            return false;
        } elseif ($book == false) {
            $msg = "The book is disabled or not exists";
            return false;
        } elseif (!($user->lent < $maxLendBook)) {
            $msg = "Almost lent maximum number of books";
            return false;
        } elseif ($book->available < 1) {
            $msg = "Book is not available to lend";
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks whether the user is eligible to request the book
     *
     * @param object $user    User
     * @param object $book    Book
     * @param object $maxVals Maximum Request and Lend Values
     * @param string $msg     String Reference variable where the reason stord
     *                        if the condition is false
     *
     * @return boolean
     */
    public function checkRequestCondition(
        object $user,
        object $book,
        object $maxVals,
        string &$msg = null
    ): bool {
        if (!$this->checkLendCondition($user, $book, $maxVals->maxBookLend, $msg)) {
            return false;
        } elseif (!($user->request < $maxVals->maxBookRequest)) {
            $msg = "You almost requested maximum number of book "
                . "and can't request a book anymore";
            return false;
        }
        return true;
    }

    /**
     * Calculates the fine amount for the issued books
     *
     * @param array  $issuedBooks  Issued books
     * @param object $fineSettings Fine Amount and Maximum lend days
     *
     * @return array
     */
    public function calculateFine(array $issuedBooks, object $fineSettings): array
    {
        foreach ($issuedBooks as $issuedBook) {
            if ($issuedBook->status == "Issued") {
                $issuedBook->fine = ($issuedBook->days > $fineSettings->maxLendDays)
                    ? (
                        "Rs. "
                         . (
                             ($issuedBook->days - $fineSettings->maxLendDays)
                             * $fineSettings->fineAmtPerDay
                         )
                    )
                    : $issuedBook->fine;
            }
        }
        return $issuedBooks;
    }
}
