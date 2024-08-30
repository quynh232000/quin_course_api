<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseStep;
use App\Models\Note;
use App\Models\Response;
use Exception;
use Illuminate\Http\Request;
use Validator;

class NoteController extends Controller
{

    /**
     * @OA\Post(
     *      path="/api/notes/create",
     *      operationId="create_note",
     *      tags={"Note"},
     *      summary="Create a new create_note",
     *      description="Returns new new create_note information",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="note",
     *                     description="Your note",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="time",
     *                     description="Note in time video: 1:20",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="step_id",
     *                     description="Step ID",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function create_note(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'note' => 'required|string',
                'time' => 'required',
                'step_id' => 'required'
            ]);
            if ($validate->fails()) {
                return Response::json(false, 'Validation failed', $validate->errors());
            }
            $step = CourseStep::find($request->step_id);
            if (!$step) {
                return Response::json(false, 'Step not found');
            }
            //    check enrollment this course 
            if (!$step->is_enrollment_course(auth('api')->id())) {
                return Response::json(false, 'You are not enrolled in this course');
            }
            // check 
            $note = Note::create([
                'note' => $request->note,
                'user_id' => auth('api')->id(),
                'step_id' => $request->step_id,
                'time' => $request->time
            ]);
            $note->step = $note->step;
            return Response::json(true, 'Note created successfully', $note);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *      path="/api/notes/my_notes/{step_id}",
     *      operationId="my_notes",
     *      tags={"Note"},
     *      summary="Filter my_notes",
     *      description="Returns list my_notes",
     *      @OA\Parameter(
     *         description="ID current step",
     *         in="path",
     *         name="step_id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }},
     *      @OA\Parameter(
     *         description="Type: course or section",
     *         in="query",
     *         name="type",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Sort: latest | oldest",
     *         in="query",
     *         name="sort",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     * 
     *     
     * )
     */
    public function get_my_notes(Request $request, $step_id)
    {
        try {
            if (!$step_id) {
                return Response::json(false, 'Required Step ID to get your notes');
            }
            $step = CourseStep::find($step_id);
            if (!$step) {
                return Response::json(false, 'Step not found');
            }
            //    check enrollment this course
            if (!$step->is_enrollment_course(auth('api')->id())) {
                return Response::json(false, 'You are not enrolled in this course');
            }
            $step_ids = [];
            if ($request->type && $request->type == 'course') {
                $step_ids = $step->all_course_steps();
            } else {
                $step_ids = $step->sibling_steps();
            }
            if ($request->sort && $request->sort == 'latest') {
                $sort = 'asc';
            } else {
                $sort = 'desc';
            }
            $notes = Note::whereIn('step_id', $step_ids)->where('user_id', auth('api')->id())->with('step')->orderBy('created_at', $sort)->get();
            return Response::json(true, 'Notes found successfully', $notes);

        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *      path="/api/notes/delete/{note_id}",
     *      operationId="delete_note",
     *      tags={"Note"},
     *      summary="delete_note ",
     *      description="delete_note",
     *     @OA\Parameter(
     *          name="note_id",
     *          description="note_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function delete_note($note_id)
    {
        try {
            if (!$note_id) {
                return Response::json(false, 'Required Note ID to delete note');
            }
            $note = Note::find($note_id);
            if (!$note) {
                return Response::json(false, 'Note not found');
            }
            if ($note->user_id != auth('api')->id()) {
                return Response::json(false, 'You are not owner of this note');
            }
            $note->delete();
            return Response::json(true, 'Note deleted successfully', $note);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *      path="/api/notes/update/{note_id}",
     *      operationId="update_note",
     *      tags={"Note"},
     *      summary="update_note",
     *      description="update_note",
     *     @OA\Parameter(
     *          name="note_id",
     *          description="note_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="note",
     *                     description="Your note",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function update_note(Request $request, $note_id)
    {
        try {
            if (!$note_id) {
                return Response::json(false, 'Required Note ID to update note');
            }
            $note = Note::find($note_id);
            if (!$note) {
                return Response::json(false, 'Note not found');
            }
            if ($note->user_id != auth('api')->id()) {
                return Response::json(false, 'You are not owner of this note');
            }
            $validate = Validator::make($request->all(), [
                'note' => 'required|string'
            ]);
            if ($validate->fails()) {
                return Response::json(false, 'Validation failed', $validate->errors());
            }
            $note->note = $request->note;
            $note->save();
            return Response::json(true, 'Update note successfully', $note);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }
}
