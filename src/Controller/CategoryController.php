<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CategoryController
 * @package App\Controller
 * @Route("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * The controller for the category add form
     * Display the form or deal with it
     *
     * @Route("/new", name="new")
     * @param Request $request
     * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function new(Request $request) : Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($category);
            // Flush the persisted object
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie ajoutée');

            // Finally redirect to categories list
            return $this->redirectToRoute('category_index');
        }
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{categoryName}", methods={"GET"}, name="show")
     * @param string $categoryName
     * @param CategoryRepository $categoryRepository
     * @param ProgramRepository $programRepository
     * @return Response
     */
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository ): Response
    {
        $category = $categoryRepository->findBy(['name' => $categoryName]);
        $programs = $programRepository->findBy(
            ['category' => $category],
            ['id' => 'ASC'],
            3
        );

        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'category' => $categoryName
        ]);
    }
}
