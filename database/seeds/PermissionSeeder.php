<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/restrict', 'title' => 'restrict middleware']);

        //import
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'import/import', 'title' => 'import']);

        //Message Permissiosns
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/send', 'title' => 'send messages']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/delete-for-all', 'title' => 'delete messages for all']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/delete-for-me', 'title' => 'delete messages for me']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/seen', 'title' => 'seen messages']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/get-from-to', 'title' => 'get messages from to']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/add-send-permission-for-role', 'title' => 'add permission to send message from role to role']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/mythreads', 'title' => 'my chat threads']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/users-assosiated-roles', 'title' => 'get roles assosiated with users']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/bulk-messages', 'title' => 'send message to bulk users']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/search-messages', 'title' => 'search messages']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'messages/search-specific-thread', 'title' => 'search specific chat thread']);

        //Notifications Permissiosns
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'notifications/get-all', 'title' => 'get all notifications']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'notifications/get-unread', 'title' => 'get unread notifications']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'notifications/mark-as-read', 'title' => 'mark notification as read']);
        // Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'notifications/get-for-user', 'title' => 'get user notifications']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'notifications/delete-duration', 'title' => 'delete notifications within a period']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'notifications/seen', 'title' => 'seen notifications']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'notifications/send', 'title' => 'send notifications']);

        //Spatie Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'roles/add', 'title' => 'Add Role' , 'dashboard' => 1, 'icon' => 'Role']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'roles/delete', 'title' => 'delete role']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'roles/get', 'title' => 'Roles Management', 'dashboard' => 1,  'icon' => 'Role']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'roles/update', 'title' => 'update role']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'roles/assign', 'title' => 'assign role to user']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'permissions/assign-to-role', 'title' => 'assign permission to role']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'roles/revoke-from-user', 'title' => 'revoke role from user']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'permissions/revoke-from-role', 'title' => 'revoke permission from role']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'spatie/list-permissions-and-roles', 'title' => 'list all permissions and roles']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'permissions/assign-to-user', 'title' => 'assign permission to user']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'spatie/list-role-with-permissions', 'title' => 'list role with permissions']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'spatie/get-role-with-permissions', 'title' => 'get role with permissions']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'spatie/add-role-with-permissions', 'title' => 'add role with permissions']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'spatie/export', 'title' => 'export roles and permissions']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'spatie/import', 'title' => 'import roles and permissions']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'permissions/get-permission-of-user', 'title' => 'get user permission']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'permissions/check-user-has-permission', 'title' => 'check user has permission']);

        //Year Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'year/add', 'title' => 'add year']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'year/get', 'title' => 'get year']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'year/update', 'title' => 'update year']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'year/delete', 'title' => 'delete year']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'year/set-current', 'title' => 'set current year']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'year/get-all', 'title' => 'get all years']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'year/get-my-years', 'title' => 'get all my years']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'year/export', 'title' => 'export all years']);


        //Type Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'type/delete', 'title' => 'delete type']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'type/add', 'title' => 'add type']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'type/get-all', 'title' => 'get all types']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'type/get', 'title' => 'get type']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'type/get-my-types', 'title' => 'get my type']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'type/update', 'title' => 'update type']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'type/assign', 'title' => 'assign type']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'type/export', 'title' => 'export all types']);


        //Level Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'level/add', 'title' => 'add level']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'level/update', 'title' => 'update level']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'level/get-all', 'title' => 'get all levels']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'level/get', 'title' => 'get level']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'level/get-my-levels', 'title' => 'get my levels']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'level/delete', 'title' => 'delete level']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'level/assign', 'title' => 'assign level']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'level/export', 'title' => 'export all levels']);

        //Class Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'class/add', 'title' => 'add class']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'class/get-all', 'title' => 'get all classes']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'class/get-my-classes', 'title' => 'get my classes']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'class/get', 'title' => 'get class']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'class/update', 'title' => 'update class']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'class/delete', 'title' => 'delete class']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'class/assign', 'title' => 'assign class']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'class/get-lessons', 'title' => 'get class lessons']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'class/export', 'title' => 'export all classes']);


        //Segment Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'segment/add', 'title' => 'add segment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'segment/delete', 'title' => 'delete segment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'segment/assign', 'title' => 'assign segment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'segment/update', 'title' => 'update segment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'segment/get-all', 'title' => 'get all segment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'segment/get', 'title' => 'get segment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'segment/set-current', 'title' => 'set current segment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'segment/get-my-segments', 'title' => 'get my segment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'segment/export', 'title' => 'export all segments']);


        //Cetegory Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'category/add', 'title' => 'add category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'category/update', 'title' => 'update category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'category/delete', 'title' => 'delete category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'category/get-all', 'title' => 'get all categories']);

        //management
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'management/get', 'title' => 'Course Management' , 'dashboard' => 1, 'icon' => 'university']);
        
        //Course Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/add', 'title' => 'add course' , 'dashboard' => 0, 'icon' => 'Course']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/update', 'title' => 'update course']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/delete', 'title' => 'delete course']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/get-all', 'title' => 'All Courses' , 'dashboard' => 1, 'icon' => 'Course']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/all-courses', 'title' => 'get all my courses']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/my-courses', 'title' => 'get current courses']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/past-courses', 'title' => 'get past courses']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/future-courses', 'title' => 'get future courses']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/layout', 'title' => 'course layout']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/optional', 'title' => 'get optional courses']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/assgin-course-to', 'title' => 'assign course']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/course-with-teacher', 'title' => 'get course with teacher']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/sorted-componenets', 'title' => 'get course with sorted components']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/toggle/letter', 'title' => 'toggle letter in course']);
        // Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/course/getUserCourseLessons', 'title' => 'course sorted components']);
        // Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/course/current_courses', 'title' => 'current courses']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/count-components', 'title' => 'get count of all component']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/chain', 'title' => 'get all courses with chain']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/components', 'title' => 'get all course with components']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/lessons', 'title' => 'get all course with lessons']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/get-classes-by-course', 'title' => 'get all classes by course']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/get-courses-by-classes', 'title' => 'get all courses by classes']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/export', 'title' => 'export courses']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/teachers', 'title' => 'view course teachers']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/participants', 'title' => 'view course participants']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/progress-bar', 'title' => 'view course progress bar']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'course/template', 'title' => 'apply course template']);

        //Enroll Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/user', 'title' => 'Staff Enrollment' , 'dashboard' => 1, 'icon' => 'Star']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/un-enroll-single-user', 'title' => 'un-enroll user']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/get-enrolled-courses', 'title' => 'get enrolled courses']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/mandatory', 'title' => 'Student Enrollment' , 'dashboard' => 1 , 'icon' => 'Star']);
        // Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/bulk-of-exist-users', 'title' => 'enroll bulk of exist users(file)']);
        // Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/add-and-enroll-bulk-of-new-users', 'title' => 'add and enroll bulk of new users(file)']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/enrolled-users', 'title' => 'get enrolled users']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/get-unenroll-users', 'title' => 'get unenrolled users']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/get-unenrolled-users-Bulk', 'title' => 'get bulk of unenrolled users']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/users', 'title' => 'enroll users with chain']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/migrate-user', 'title' => 'migrate user to another class']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/get-unenroll-users-role', 'title' => 'Get unenrolled users']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/get', 'title' => 'Get Chain']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'enroll/delete', 'title' => 'Destroy Chain']);


        //Events
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'event/add', 'title' => 'Add event to users']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'event/delete', 'title' => 'delete event']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'event/update', 'title' => 'update event']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'event/my-events', 'title' => 'get my event']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'event/all-events', 'title' => 'get all events']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'event/add-bulk', 'title' => 'add bulk events']);


        //Contact Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'contact/add', 'title' => 'add contact']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'contact/get', 'title' => 'get contact']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'contact/search', 'title' => 'search contact']);

        //USER CRUD Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/add', 'title' => 'add user']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/update', 'title' => 'update user']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/delete', 'title' => 'delete user']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/get-all', 'title' => 'Users' , 'dashboard' => 1, 'icon' => 'User']);
        // Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/user/list', 'title' => 'get all user']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/suspend', 'title' => 'suspend user']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/un-suspend', 'title' => 'un suspend user']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/show-hide-real-pass', 'title' => 'shor and hide real password']);
        // Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/user/Show-and-hide-real-password-with-permission', 'title' => 'Show and hide real password with permission']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/parent-child', 'title' => 'get family']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/get-by-id', 'title' => 'get user by id']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/get-with-role-cs', 'title' => 'get users in course with filter role_id']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/filter-with-role', 'title' => 'filter all users with role']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/current-child', 'title' => 'set current child']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/get-current-child', 'title' => 'get current child']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/get-someone-child', 'title' => 'get child by parent_id']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/get-someone-parent', 'title' => 'get parent by child_id']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/get-my-child', 'title' => 'get my child']);
        // Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/search-all-users', 'title' => 'search all users']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/get-my-users', 'title' => 'get all my users']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/generate-username-password', 'title' => 'generate username and password']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/GetAllCountries', 'title' => 'Get all countries']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/GetAllNationalities', 'title' => 'Get all nationalities']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/set-parent-child', 'title' => 'Assign Parent','dashboard' => 1]);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/export', 'title' => 'Export Users']);


        //Components Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'component/get', 'title' => 'get component']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'component/install', 'title' => 'install component']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'component/uninstall', 'title' => 'uninstall component']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'component/toggle', 'title' => 'toggle component']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'component/sort', 'title' => 'sort component']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'component/change-color', 'title' => 'change component color']);

        //Announcements Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'announcements/delete', 'title' => 'delete announcements']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'announcements/send', 'title' => 'send announcements']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'announcement/filter-chain', 'title' => 'Announcement Filter Chain']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'announcements/get', 'title' => 'View Announcements', 'dashboard' => 1 , 'icon'=> 'announcement']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'announcements/update', 'title' => 'update announcements']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'announcements/getbyid', 'title' => 'get announcements by id']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'announcements/get-unread', 'title' => 'get unread announcements']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'announcements/mark-as-read', 'title' => 'mark announcements as read']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'announcements/my', 'title' => 'My Announcements'  , 'dashboard' => 1, 'icon'=> 'announcement']);


        //Calendar Permission
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'calendar/get', 'title' => 'get calendar']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'calendar/weekly', 'title' => 'get weekly calendar']);

        //Import
        // Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'import', 'title' => 'import excel sheet']);

        //Language Permission
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'languages/get', 'title' => 'Get Languages']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'languages/add', 'title' => 'Add Language']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'languages/update', 'title' => 'Update Language']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'languages/delete', 'title' => 'Delete Language']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'languages/dictionary', 'title' => 'Get Dictionary']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/language', 'title' => 'change my language']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'languages/activate', 'title' => 'Activate language']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'languages/deactivate', 'title' => 'Dea-ctivate language']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'languages/set-default', 'title' => 'Set default language']);



        
        //Lesson Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'lesson/add', 'title' => 'add lesson']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'lesson/get', 'title' => 'get lesson']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'lesson/delete', 'title' => 'delete lesson']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'lesson/update', 'title' => 'update lesson']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'lesson/sort', 'title' => 'sort lesson']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'lesson/addBulk', 'title' => 'add bulk lesson']);

        //Grade Ctegory
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/add', 'title' => 'add grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/get', 'title' => 'get grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/delete', 'title' => 'delete grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/update', 'title' => 'update grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/move', 'title' => 'change parent of grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/tree', 'title' => 'grade category tree']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/bulk-update', 'title' => 'update bulk grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/bulk-delete', 'title' => 'delete bulk grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/bulk', 'title' => 'add bulk grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/assign-bulk', 'title' => 'assign bulk grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/bulk-get', 'title' => 'get bulk grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/bulk-get-level', 'title' => 'get bulk grade category by levels']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/bulk-all-get', 'title' => 'get bulk grade category with chain']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/chain-categories', 'title' => 'get all chain grade category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/grades', 'title' => 'Grades', 'icon' => 'grade']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/category/get-gradecategories', 'title' => 'Get Grade Categories']);


        //Grade Item
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/add', 'title' => 'add grade item']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/get', 'title' => 'get grade item']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/delete', 'title' => 'delete grade item']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/update', 'title' => 'update grade item']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/move-category', 'title' => 'change parent grade category of grade item']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/AddBulk', 'title' => 'add grade items category']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/bulk-delete', 'title' => 'delete bulk grade items']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/bulk-update', 'title' => 'update bulk grade items']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/bulk-assign', 'title' => 'assign bulk grade items']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/get-bulk', 'title' => 'get bulk grade items']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/grading-method', 'title' => 'get all grading methods']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/get-allowed-functions', 'title' => 'get allowed mathematical functions']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/item/override', 'title' => 'override grade item']);

        //User Grade
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/user/add', 'title' => 'add user grade']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/user/get', 'title' => 'get user grade']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/user/update', 'title' => 'update user grade']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/user/delete', 'title' => 'delete user grade']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/user/course-grade', 'title' => 'course\'s user and grades']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'allow-edit-profiles', 'title' => 'allow user to edit any profile']);

        //Grades Reports
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/report/grader', 'title' => 'grader report']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/report/overview', 'title' => 'overview report']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/report/user', 'title' => 'get user grades']);
        //Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/user/getallusersgrades', 'title' => 'get all users grades']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'grade/report/over-all', 'title' => 'get all users grades in courses']);
        
        //dashboard
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'dashboard/toggle', 'title' => 'toggle dashboard']);
        // Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'dashboard/dashboard', 'title' => 'show dashboard']);

        //scale
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'scale/add', 'title' => 'Add Scale']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'scale/update', 'title' => 'Update Scale']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'scale/delete', 'title' => 'Delete Scale']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'scale/get', 'title' => 'Get Scale']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'scale/get-with-course', 'title' => 'Get Scale with course']);

        //scale
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'letter/add', 'title' => 'Add Letter']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'letter/update', 'title' => 'Update Letter']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'letter/delete', 'title' => 'Delete Letter']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'letter/get', 'title' => 'Get Letter']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'letter/assign', 'title' => 'assign Letter']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'letter/get-with-course', 'title' => 'get letter with course']);


        //contract
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'contract/add', 'title' => 'add contract']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'contract/update', 'title' => 'update contract']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'contract/restrict', 'title' => 'restrict contract']);

        //payment
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'payment/add', 'title' => 'add payment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'payment/delete', 'title' => 'delete payment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'payment/postponed-payment', 'title' => 'postpond payment']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'payment/pay-payment', 'title' => 'pay payment']);
        
        //chat
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'chat/add-room', 'title' => 'add room']);

        //report
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'reports/active_users', 'title' => 'Active users report', 'dashboard' => 1, 'icon'=> 'Report']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'reports/course_progress', 'title' => 'view course progress report' , 'dashboard' => 1, 'icon'=> 'Report']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'reports/in_active_users', 'title' => 'In active users report', 'dashboard' => 1, 'icon'=> 'Report']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'reports/seen_report', 'title' => 'Seen report']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'reports/overall_seen_report', 'title' => 'Overall seen report','dashboard' => 1, 'icon'=> 'Report']);
        
        //site internal permessions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/user/search-all-users', 'title' => 'Search all users assigned to my course segments and search all site wide for users give permission to search site wide']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/show/real-password', 'title' => 'Show Real Password']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/course/teacher', 'title' => 'detect course\'s teacher']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/course/student', 'title' => 'detect course\'s student']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/show-all-courses', 'title' => 'admin permission']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/update-password', 'title' => 'update password']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/update-username', 'title' => 'update username']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/show/username', 'title' => 'show username']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'site/show/as-participant', 'title' => 'show as participant']);

        //Timeline Resources Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'timeline/store', 'title' => 'Store Timeline']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'timeline/get', 'title' => 'Get Timeline']);
                    
        //Materials Resources Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'material/get', 'title' => 'Get Materials']);

        //logs
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'user/logs', 'title' => 'Logs', 'dashboard' => 1 , 'icon'=> 'User']);

        //system settings
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'settings/general', 'title' => 'General Settings', 'dashboard' => 1 , 'icon'=> 'Settings']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'settings/create_assignment_extensions', 'title' => 'Manage create assignment extensions']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'settings/extensions', 'title' => 'general extension']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'settings/submit_assignment_extensions', 'title' => 'Manage submit assignment extensions']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'settings/upload_media_extensions', 'title' => 'Manage upload media extensions']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'settings/upload_file_extensions', 'title' => 'Manage upload file extensions']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'settings/logo', 'title' => 'Set|Delete Logo']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'settings/grade_pass', 'title' => 'Manage grade to pass percentage']);

        //Topic Permissions
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'topic/crud', 'title' => 'Topic Crud']);
        Permission::firstOrCreate(['guard_name' => 'api', 'name' => 'topic/get', 'title' => 'Get Topic']);        
    }
}
