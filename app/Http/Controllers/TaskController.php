<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Validator;
use Throwable;

class TaskController extends Controller
{
    /**
     * Registration
     */
    public function createTask(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'subject' => 'required|max:255',
                'description' => 'required',
                'start_date' => 'required',
                'due_date' => 'required',
                'status' => ['required', 'in:"New","Incomplete","Complete"'],
                'priority' => ['required', 'in:"High","Medium","Low"']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status' => 'error',
                    'data' => ['validationErrorList' => $errors]
                ], 200);
            }
            $task = Task::create([
                'subject' => $request->subject,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'due_date' => $request->due_date,
                'status' => $request->status,
                'priority' => $request->priority,
                'created_by_id' => auth()->user()->id
            ]);
            $note_subject = $request->note_subject;
            $note_note = $request->note_note;
            if ($note_subject) {
                foreach ($note_subject as $key => $subject) {
                    $innerFileLength = $request->get('form_attachment_inner_length_' . $key);
                    if ($innerFileLength > 0 && !$subject) {
                        return response()->json([
                            'status' => 'error',
                            'data' => ['messageList' => ["Subject cannot be blank if file is attached."]]
                        ], 200);
                    } else if (!$subject)
                        continue;
                    $attachments = [];
                    for ($j = 0; $j < $innerFileLength; $j++) {
                        if ($request->hasFile('form_attachment_' . $key . '_' . $j)) {
                            $innerFile = $request->file('form_attachment_' . $key . '_' . $j);
                            $attachments[$j]['file_name'] = $innerFile->getClientOriginalName();
                            $attachments[$j]['extension'] = $innerFile->getClientOriginalExtension();
                            $attachments[$j]['system_file_name']  = $this->uniqueDocumentName() . '.' . $attachments[$j]['extension'];
                            $innerFile->move(public_path('storage'), $attachments[$j]['system_file_name']);
                        }
                    }
                    Note::create([
                        'subject' => $subject,
                        'note' => $note_note[$key] ?? '',
                        'attachments' => $attachments,
                        'task_id' => $task->id
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => ['messageList' => [$task->id . " Task created successfully."]]
            ], 200);
        } catch (Throwable $ex) {
            return response()->json([
                'status' => 'error',
                'data' => ['messageList' => ["Internal Error."]]
            ], 200);
        }
    }

    private function uniqueDocumentName($sep = '-')
    {
        return time() . $sep . rand(11111, 99999) . $sep . auth()->user()->id;
    }

    /**
     * Login
     */
    public function listTask(Request $request)
    {
        $taskList = Task::withWhereHas('notes', function ($q) use ($request) {
            if ($request->note)
                $q->where('note', 'LIKE', "%$request->note%");
        });
        if ($request->status)
            $taskList = $taskList->where('status', $request->status);
        if ($request->priority)
            $taskList = $taskList->where('priority', $request->priority);
        if ($request->due_date)
            $taskList = $taskList->where('due_date', $request->due_date);
        if (config('database.default') == 'mysql') {
            $priorities = ['High', 'Medium', 'Low'];
            $priorityString = implode('","', $priorities);
            $taskList = $taskList->orderByRaw('FIELD(priority, "' . $priorityString . '")');
        } else if (config('database.default') == 'pgsql')
            $taskList = $taskList->orderByRaw("CASE priority
                    WHEN 'High' THEN 1
                    WHEN 'Medium' THEN 2
                    WHEN 'Low' THEN 3
                END");
        $taskList = $taskList
            ->withCount('notes')->orderByDesc("notes_count")
            ->has('notes', '>=', 1)
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => ['taskList' => $taskList]
        ], 200);
    }
}
