<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QNA;
use App\Models\QNAReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QnaController extends Controller
{
    public function getQna($id)
    {
        $qnas = QNA::where('product_id',$id)->with(['user','qnaReplys','qnaReplys.user'])->paginate(15);
        return response()->json(compact('qnas'));
    }

    public function postQna(Request $request)
    {
        $this->authorize('authCheck');
        $this->validate($request, [
            'message' => 'required|max:1500',
        ]);

        $limit = QNA::where('user_id',Auth::id())->where('product_id',$request->product_id)->count();

        if($limit < '5') {
            $qna = new QNA();
            $qna->user_id = Auth::id();
            $qna->product_id = $request->product_id;
            $qna->message = $request->message;
            $qna->save();
        } else {
            return response()->json('You Can not Ask More than 5 question', 404);
        }
    }

    public function postQnaReplay(Request $request)
    {
        $this->authorize('authCheck');
        $this->validate($request, [
            'message' => 'required|max:1500',
        ]);
        $qnaReply = new QNAReply();
        $qnaReply->user_id = Auth::id();
        $qnaReply->q_n_a_id = $request->qna_id;
        $qnaReply->message = $request->message;
        $qnaReply->save();
    }

    public function deleteQna($id)
    {
        $this->authorize('authCheck');
        QNA::where('id', $id)->first()->delete();
    }

    public function deleteQnaReplay($id)
    {
        $this->authorize('authCheck');
        QNAReply::where('id', $id)->first()->delete();
    }
}
