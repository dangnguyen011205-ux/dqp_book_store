<?php
namespace Controllers;

use Core\Controller;
use Models\Book;
use Models\Category;
use Models\Author;
use Models\Review;

class HomeController extends Controller {
    protected Book $bookModel;
    protected Category $categoryModel;
    protected Author $authorModel;
    protected Review $reviewModel;

    public function __construct() {
        $this->bookModel = new Book();
        $this->categoryModel = new Category();
        $this->authorModel = new Author();
        $this->reviewModel = new Review();
    }

    public function index(): void {
        $categories = $this->categoryModel->getAll();
        $flashDeals = $this->bookModel->getFlashDeals(4);
        $bestsellers = $this->bookModel->getBestsellers(5);
        $authors = $this->authorModel->getFeatured(6);
        $featuredReviews = $this->reviewModel->getFeaturedReviews(3);

        $this->view('client.home', [
            'title'           => APP_NAME . ' - ' . APP_SLOGAN . ' | Thư Viện Tinh Hoa',
            'categories'      => $categories,
            'flashDeals'      => $flashDeals,
            'bestsellers'     => $bestsellers,
            'authors'         => $authors,
            'featuredReviews' => $featuredReviews
        ]);
    }
}
