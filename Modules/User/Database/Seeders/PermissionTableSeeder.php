<?php

namespace Modules\User\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\User\Entities\Permission;
use Modules\User\Entities\PermissionGroup;
use Modules\User\Entities\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        PermissionGroup::insert([
            ['name' => 'Permission', 'slug' => 'permission', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Role', 'slug' => 'role', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Company', 'slug' => 'company', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Floor', 'slug' => 'floor', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'User', 'slug' => 'user', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'Zone', 'slug' => 'zone', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Terminal', 'slug' => 'terminal', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'Position', 'slug' => 'position', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Department', 'slug' => 'department', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Division', 'slug' => 'division', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Attendance', 'slug' => 'attendance', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Staff', 'slug' => 'staff', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Access', 'slug' => 'access', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'DayOff', 'slug' => 'day-off', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Position Grade', 'slug' => 'position-grade', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Device', 'slug' => 'device', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'Room', 'slug' => 'room', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Guest', 'slug' => 'guest', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Invite', 'slug' => 'guest', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Booking', 'slug' => 'booking', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Application', 'slug' => 'application', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        Permission::insert([
            ['name' => 'permission.indexWeb', 'label' => 'View Permission', 'guard_name' => 'web', 'permission_group_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'permission.create', 'label' => 'Create Permission', 'guard_name' => 'web', 'permission_group_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'permission.update', 'label' => 'Update Permission', 'guard_name' => 'web', 'permission_group_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'permission.destroy', 'label' => 'Delete Permission', 'guard_name' => 'web', 'permission_group_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'permission-groups', 'label' => 'List Permission Group', 'guard_name' => 'web', 'permission_group_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'permission-group.create', 'label' => 'Create Permission Group', 'guard_name' => 'web', 'permission_group_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'role.indexWeb', 'label' => 'View Role', 'guard_name' => 'web', 'permission_group_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'role.create', 'label' => 'Create Role', 'guard_name' => 'web', 'permission_group_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'role.update', 'label' => 'Update Role', 'guard_name' => 'web', 'permission_group_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'role.destroy', 'label' => 'Delete Role', 'guard_name' => 'web', 'permission_group_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'role.sync-permission', 'label' => 'Sync-Permission Role', 'guard_name' => 'web', 'permission_group_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'company.indexWeb', 'label' => 'View Company', 'guard_name' => 'web', 'permission_group_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'company.create', 'label' => 'Create Company', 'guard_name' => 'web', 'permission_group_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'company.update', 'label' => 'Update Company', 'guard_name' => 'web', 'permission_group_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'company.destroy', 'label' => 'Update Company', 'guard_name' => 'web', 'permission_group_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'floor.indexWeb', 'label' => 'View Floor', 'guard_name' => 'web', 'permission_group_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'floor.create', 'label' => 'Create Floor', 'guard_name' => 'web', 'permission_group_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'floor.update', 'label' => 'Update Floor', 'guard_name' => 'web', 'permission_group_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'floor.destroy', 'label' => 'Update Floor', 'guard_name' => 'web', 'permission_group_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'user.indexWeb', 'label' => 'View User', 'guard_name' => 'web', 'permission_group_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'user.create', 'label' => 'Create User', 'guard_name' => 'web', 'permission_group_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'user.update', 'label' => 'Update User', 'guard_name' => 'web', 'permission_group_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'user.destroy', 'label' => 'Delete User', 'guard_name' => 'web', 'permission_group_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'zone.indexWeb', 'label' => 'View Zone', 'guard_name' => 'web', 'permission_group_id' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'zone.create', 'label' => 'Create Zone', 'guard_name' => 'web', 'permission_group_id' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'zone.update', 'label' => 'Update Zone', 'guard_name' => 'web', 'permission_group_id' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'zone.destroy', 'label' => 'Delete Zone', 'guard_name' => 'web', 'permission_group_id' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'terminal.indexWeb', 'label' => 'View Terminal', 'guard_name' => 'web', 'permission_group_id' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'terminal.create', 'label' => 'Create Terminal', 'guard_name' => 'web', 'permission_group_id' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'terminal.update', 'label' => 'Update Terminal', 'guard_name' => 'web', 'permission_group_id' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'terminal.destroy', 'label' => 'Delete Terminal', 'guard_name' => 'web', 'permission_group_id' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'terminal.request.logs', 'label' => 'View Terminal Request Logs', 'guard_name' => 'web', 'permission_group_id' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'terminal.action.logs', 'label' => 'View Terminal Action Logs', 'guard_name' => 'web', 'permission_group_id' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'position.indexWeb', 'label' => 'View Position', 'guard_name' => 'web', 'permission_group_id' => 8, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'position.create', 'label' => 'Create Position', 'guard_name' => 'web', 'permission_group_id' => 8, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'position.update', 'label' => 'Update Position', 'guard_name' => 'web', 'permission_group_id' => 8, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'position.destroy', 'label' => 'Delete Position', 'guard_name' => 'web', 'permission_group_id' => 8, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'department.indexWeb', 'label' => 'View Department', 'guard_name' => 'web', 'permission_group_id' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'department.create', 'label' => 'Create Department', 'guard_name' => 'web', 'permission_group_id' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'department.update', 'label' => 'Update Department', 'guard_name' => 'web', 'permission_group_id' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'department.destroy', 'label' => 'Delete Department', 'guard_name' => 'web', 'permission_group_id' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'division.indexWeb', 'label' => 'View Division', 'guard_name' => 'web', 'permission_group_id' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'division.create', 'label' => 'Create Division', 'guard_name' => 'web', 'permission_group_id' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'division.update', 'label' => 'Update Division', 'guard_name' => 'web', 'permission_group_id' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'division.destroy', 'label' => 'Delete Division', 'guard_name' => 'web', 'permission_group_id' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'attendance.list', 'label' => 'List Attendance', 'guard_name' => 'web', 'permission_group_id' => 11, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'attendance.calendar', 'label' => 'Calendar Attendance', 'guard_name' => 'web', 'permission_group_id' => 11, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'attendance.user-detail', 'label' => 'User Details Attendance', 'guard_name' => 'web', 'permission_group_id' => 11, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'staff.indexWeb', 'label' => 'View Staff', 'guard_name' => 'web', 'permission_group_id' => 12, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'staff.update', 'label' => 'Update Staff', 'guard_name' => 'web', 'permission_group_id' => 12, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'staff.schedule', 'label' => 'Manage schedule', 'guard_name' => 'web', 'permission_group_id' => 12, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'staff.gadget', 'label' => 'Manage gadgets', 'guard_name' => 'web', 'permission_group_id' => 12, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'access.indexWeb', 'label' => 'View Access', 'guard_name' => 'web', 'permission_group_id' => 13, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'access.control', 'label' => 'Control Access', 'guard_name' => 'web', 'permission_group_id' => 13, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'day-off.indexWeb', 'label' => 'View Days Off', 'guard_name' => 'web', 'permission_group_id' => 14, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'day-off.create', 'label' => 'Create Days Off', 'guard_name' => 'web', 'permission_group_id' => 14, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'day-off.update', 'label' => 'Update Days Off', 'guard_name' => 'web', 'permission_group_id' => 14, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'day-off.destroy', 'label' => 'Delete Days Off', 'guard_name' => 'web', 'permission_group_id' => 14, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'position-grade.indexWeb', 'label' => 'View Position Grade', 'guard_name' => 'web', 'permission_group_id' => 15, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'position-grade.create', 'label' => 'Create Position Grade', 'guard_name' => 'web', 'permission_group_id' => 15, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'position-grade.update', 'label' => 'Update Position Grade', 'guard_name' => 'web', 'permission_group_id' => 15, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'position-grade.destroy', 'label' => 'Delete Position Grade', 'guard_name' => 'web', 'permission_group_id' => 15, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'device.indexWeb', 'label' => 'View Device', 'guard_name' => 'web', 'permission_group_id' => 16, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'device.create', 'label' => 'Create Device', 'guard_name' => 'web', 'permission_group_id' => 16, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'device.update', 'label' => 'Update Device', 'guard_name' => 'web', 'permission_group_id' => 16, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'device.destroy', 'label' => 'Delete Device', 'guard_name' => 'web', 'permission_group_id' => 16, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'room.indexWeb', 'label' => 'View Room', 'guard_name' => 'web', 'permission_group_id' => 17, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'room.create', 'label' => 'Create Room', 'guard_name' => 'web', 'permission_group_id' => 17, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'room.update', 'label' => 'Update Room', 'guard_name' => 'web', 'permission_group_id' => 17, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'room.destroy', 'label' => 'Delete Room', 'guard_name' => 'web', 'permission_group_id' => 17, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'guest.indexWeb', 'label' => 'View Guest', 'guard_name' => 'web', 'permission_group_id' => 18, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'guest.create', 'label' => 'Create Guest', 'guard_name' => 'web', 'permission_group_id' => 18, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'guest.update', 'label' => 'Update Guest', 'guard_name' => 'web', 'permission_group_id' => 18, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'guest.destroy', 'label' => 'Delete Guest', 'guard_name' => 'web', 'permission_group_id' => 18, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'invite.indexWeb', 'label' => 'View Invite', 'guard_name' => 'web', 'permission_group_id' => 19, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'invite.create', 'label' => 'Create Invite', 'guard_name' => 'web', 'permission_group_id' => 19, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'invite.update', 'label' => 'Update Invite', 'guard_name' => 'web', 'permission_group_id' => 19, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'invite.destroy', 'label' => 'Delete Invite', 'guard_name' => 'web', 'permission_group_id' => 19, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'invite.approve', 'label' => 'Approve Invite', 'guard_name' => 'web', 'permission_group_id' => 19, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'invite.attach-photo', 'label' => 'Attach Photo Invite', 'guard_name' => 'web', 'permission_group_id' => 19, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'booking.indexWeb', 'label' => 'View Booking', 'guard_name' => 'web', 'permission_group_id' => 20, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'booking.create', 'label' => 'Create Booking', 'guard_name' => 'web', 'permission_group_id' => 20, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'booking.update', 'label' => 'Update Booking', 'guard_name' => 'web', 'permission_group_id' => 20, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'booking.report', 'label' => 'Booking Report', 'guard_name' => 'web', 'permission_group_id' => 20, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

            ['name' => 'application.indexWeb', 'label' => 'View Application', 'guard_name' => 'web', 'permission_group_id' => 21, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
        Role::insert([
            ['name' => 'super_admin', 'guard_name' => 'web', 'grade' => 1, 'label' => 'Super Admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'admin_security', 'label' => 'Security Admin', 'grade' => 2, 'guard_name' => 'web', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            //#############
            ['name' => 'admin_company', 'label' => 'Company Admin', 'grade' => 3, 'guard_name' => 'web', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'hr', 'label' => 'HR', 'guard_name' => 'web', 'grade' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'chief', 'label' => 'Chief', 'guard_name' => 'web', 'grade' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'user', 'label' => 'User', 'guard_name' => 'web', 'grade' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'security', 'label' => 'Security', 'guard_name' => 'web', 'grade' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'it_technician', 'label' => 'IT-Technician', 'guard_name' => 'web', 'grade' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'cleaning', 'label' => 'Cleaning', 'guard_name' => 'web', 'grade' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'supplier', 'label' => 'Supplier', 'guard_name' => 'web', 'grade' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        $permissions = Permission::all();

        Role::query()->whereNotIn('name', ['hr', 'chief', 'user', 'admin_company'])
            ->each(function ($role) use ($permissions) {
                $role->givePermissionTo($permissions);
            });


    }
}
