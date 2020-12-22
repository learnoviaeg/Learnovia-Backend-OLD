<?php

// resources/lang/en/messages.php

return [
    'error' => [
        'not_found' => 'Item is not found!',
        'parent_cannot_submit' => 'Parents can not submit answers',
        'user_not_assign' => 'This user is not assigned to this item',
        'submit_limit' => 'Sorry, you are not allowed to submit anymore',
        'not_available_now' => 'Sorry, This item is not available for you at this moment',
        'try_again' => 'Something went wrong, please try again',
        'data_invalid' => 'This data is invalid',
        'item_added_before' => 'This item added before',
        'cannot_delete' => 'This item cannot be deleted',
        'item_deleted' => 'This item has been removed',
        'incomplete_data' => 'This data is incomplete',
        'grade_less_than' => 'Please, put grade less than ',
        'no_available_data' => 'No available data to show',
        'cannot_see_feedback' => 'You are not allowed to see feedback',
        'no_active_for_lesson' => 'No active segment for this lesson',
        'already_exist' => 'Item is already exist',
    ],
    'success' => [
        'submit_success' => 'Answer submitted successfully',
        'toggle' => 'Item toggle successfully',
    ],
    'lesson' => [
        'add' => 'Lesson added successfully',
        'update' => 'Lesson updated successfully',
        'delete' => 'Lesson deleted successfully',
        'sort' => 'Lessons sorted successfully',
        'list' => 'Lessons List',
    ],
    'assignment' => [
        'add' => 'Assignment added successfully',
        'update' => 'Assignment updated successfully',
        'delete' => 'Assignment deleted successfully',
        'list' => 'Assignments List',
        'count' => 'Assignments count',
        'assignment_not_belong' => 'This assignment does not belong to this lesson',
        'assignment_object' => 'Assignment details',
        'content_only' => 'Please, enter only content',
        'file_only' => 'Please, enter only file',
        'content_or_file' => 'Please, enter content or file',
        'override' => 'Assignment date updated to selected students successfully',
        'cant_update' => 'Sorry, students answered this assignment, cannot be updated',
    ],
    'grade' => [
        'graded' => 'Grade submitted successfully',
        'update' => 'Grade edited successfully',
        'grading_method_list' => 'Grading methods list',
        'grade_category_list' => 'Grade categories list',
    ],
    'date' => [
        'end_before' => 'Please, enter due date before '
    ],
    'question' => [
        'questions_answers_count' => 'Questions number is greater than numbers of answers',
        'add' => 'Question added successfully',
        'update' => 'Question updated successfully',
        'delete' => 'Question deleted successfully',
        'list' => 'Questions List',
        'question_type_error' => 'This Question is not Essay or true and false',
    ],
    'answer' => [
        'add' => 'Answer added successfully',
        'delete' => 'Answer deleted successfully',
        'not_belong_to_question' => 'This answer is not belong to this question',
    ],
    'question_category' => [
        'add' => 'Questions category added successfully',
        'update' => 'Questions category updated successfully',
        'delete' => 'Questions category deleted successfully',
        'list' => 'Questions categories List',
    ],
    'quiz' => [
        'add' => 'Quiz added successfully',
        'update' => 'Quiz updated successfully',
        'delete' => 'Quiz deleted successfully',
        'list' => 'Quizzes List',
        'count' => 'Quizzes count',
        'invaled_feedback' => 'Invalid feedback type, feedback can not be After submission',
        'quiz_not_belong' => 'This quiz does not belong to this lesson',
        'override' => 'Quiz date updated to selected students successfully',
        'continue_quiz' => 'You can continue your last attempt',
        'students_attempts_list' => 'Students attempts list',
        'quiz_object' => 'Quiz details',
    ],
    'permissions' => [
        'no_roles_assigned' => 'There is no roles assigned to this permission'
    ],
    'users' => [
        'students_list' => 'Students list',
        'parent_choose_child'=> 'Please, choose your child first',
    ],
    'page' => [
        'add' => 'Page added successfully',
        'update' => 'Page updated successfully',
        'delete' => 'Page deleted successfully',
        'list' => 'Pages List',
        'page_not_belong' => 'This page does not belong to this lesson',
    ],
    'file' => [
        'add' => 'File uploaded successfully',
        'update' => 'File updated successfully',
        'delete' => 'File deleted successfully',
        'list' => 'Files List',
        'file_not_belong' => 'This file does not belong to this lesson',
    ],
    'media' => [
        'add' => 'Media uploaded successfully',
        'update' => 'Media updated successfully',
        'delete' => 'Media deleted successfully',
        'list' => 'Medias List',
        'media_not_belong' => 'This media does not belong to this lesson',
        'only_url_or_media' => 'Please, either upload media or add a URL',

        'image' => [
            'add' => 'Image uploaded successfully',
            'update' => 'Image updated successfully',
            'delete' => 'Image deleted successfully',
        ],
        'video' => [
            'add' => 'Video uploaded successfully',
            'update' => 'Video updated successfully',
            'delete' => 'Video deleted successfully',
        ],
        'audio' => [
            'add' => 'Audio uploaded successfully',
            'update' => 'Audio updated successfully',
            'delete' => 'Audio deleted successfully',
        ],
        'link' => [
            'add' => 'Link uploaded successfully',
            'update' => 'Link updated successfully',
            'delete' => 'Link deleted successfully',
        ],
        'url' => [
            'add' => 'URL uploaded successfully',
            'update' => 'URL updated successfully',
            'delete' => 'URL deleted successfully',
        ],
    ],
    'materials' => [
        'list' => 'Materials list',
        'count' => 'Materials count',
    ],
];