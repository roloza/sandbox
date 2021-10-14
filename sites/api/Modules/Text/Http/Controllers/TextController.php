<?php

namespace Modules\Text\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TextController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $web = new \spekulatius\phpscraper();
        $web->go('https://www.leparisien.fr/economie/le-diesel-plus-cher-quau-debut-de-la-crise-des-gilets-jaunes-lile-de-france-en-premiere-ligne-12-10-2021-WTKINK4LUVDCBAJSDQMEOXSL5Q.php');
        return response()->json([
            'message' => 'success',
            'data' => [
                'headers' => [
                    'contentType' => $web->contentType,
                    'viewport' => $web->viewport,
                    'canonical' => $web->canonical,
                    'csrfToken' => $web->csrfToken,
                    'author' => $web->author,
                    'description' => $web->description,
                    'image' => $web->image,
                    'keywords' => $web->keywords,
                    'openGraph' => $web->openGraph,
                    'twitterCard' => $web->twitterCard
                ],
                'paragraphs' => $web->paragraphs,
                'lists' => $web->lists,
                'content' => $web->cleanOutlineWithParagraphs,
                'keywords' => $web->contentKeywordsWithScores,
                'images' => $web->imagesWithDetails,
                'links' => $web->linksWithDetails
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('text::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('text::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('text::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
