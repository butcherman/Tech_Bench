<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\TechTipCommentRequest;
use App\Models\TechTip;
use App\Services\TechTip\TechTipCommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TechTipCommentController extends Controller
{
    public function __construct(protected TechTipCommentService $svc) {}

    /**
     *
     */
    public function index()
    {
        //
        return 'index';
    }

    /**
     * Save a new Tech Tip Comment
     */
    public function store(TechTipCommentRequest $request, TechTip $tech_tip): RedirectResponse
    {
        $this->svc->createComment(
            $request->safe()->collect(),
            $tech_tip,
            $request->user()
        );

        return back()->with('success', __('tips.comment.created'));
    }

    /**
     *
     */
    public function update(Request $request, string $id)
    {
        //
        return 'update';
    }

    /**
     *
     */
    public function destroy(string $id)
    {
        //
        return 'destroy';
    }

    /**
     *
     */
    public function restore(string $id)
    {
        //
        return 'restore';
    }

    /**
     *
     */
    public function forceDelete(string $id)
    {
        //
        return 'force delete';
    }
}
