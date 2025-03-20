<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">BS App</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel d-flex align-items-center mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img :src="authUser.profile.photo ? '/'+ authUser.profile.photo : '/dist/img/default.jpeg' "
                         class="img-circle elevation-2"
                         alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-flex flex-column">
                        <span class="d-block">{{ authUser?.name }}</span>
                        <small class="d-block small">{{ authUser?.role?.label }}</small>
                    </a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                           aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <el-menu
                    class="el-menu-vertical-demo"
                    background-color="transparent"
                    text-color="#fff"
                    active-text-color="#007bff"
                    :default-openeds="[prefix]"
                    :default-active="'/'+currentPath"
                    :router="true"
                    :unique-opened="true"
                >
                    <el-menu-item text-color="#fff" index="/profile" class="px-0">
                        <a href="/profile" class="el-menu-item flex-grow-1 w-100">
                            <el-icon>
                                <em class="fas fa-user"></em>
                            </el-icon>
                            <span>Профиль</span>
                        </a>
                    </el-menu-item>
                    <el-menu-item text-color="#fff" index="/profile/attendance" class="px-0">
                        <a href="/profile/attendance" class="el-menu-item flex-grow-1 w-100">
                            <el-icon>
                                <em class="fas fa-user-clock"></em>
                            </el-icon>
                            <span>Мое посещение</span>
                        </a>
                    </el-menu-item>
                    <el-menu-item index="/attendance/list" text-color="#fff" class="px-0"
                                  v-if="can('attendance.list') || is('super_admin')">
                        <a href="/attendance/list" class="el-menu-item flex-grow-1 w-100">
                            <el-icon>
                                <em class="fas fa-list-alt"></em>
                            </el-icon>
                            <span>Посещения - список</span>
                        </a>
                    </el-menu-item>
                    <el-menu-item index="/attendance/calendar" text-color="#fff" class="px-0"
                                  v-if="can('attendance.calendar') || is('super_admin')">
                        <a href="/attendance/calendar" class="el-menu-item flex-grow-1 w-100">
                            <el-icon>
                                <em class="fas fa-calendar-alt"></em>
                            </el-icon>
                            <span>Посещения - календарь</span>
                        </a>
                    </el-menu-item>
                    <el-sub-menu index="/management"
                                 v-if="can('employee.indexWeb|day-offs.indexWeb|access.indexWeb|department.index|division.index|position.index|attendance.index')">
                        <template #title>
                            <el-icon>
                                <em class="fas fa-users"></em>
                            </el-icon>
                            <span>Управление</span>
                        </template>
                        <el-menu-item index="/management/employee" text-color="#fff" class="px-0"
                                      v-if="can('employee.indexWeb')">
                            <a href="/management/employee" class="el-menu-item flex-grow-1 w-100">Сотрудники</a>
                        </el-menu-item>
                        <el-menu-item index="/management/day-offs" text-color="#fff" class="px-0"
                                      v-if="can('day-offs.indexWeb')">
                            <a href="/management/day-offs" class="el-menu-item flex-grow-1 w-100">Выходные дни</a>
                        </el-menu-item>
                        <el-menu-item index="/management/access" text-color="#fff" class="px-0"
                                      v-if="can('access.indexWeb')">
                            <a href="/management/access"
                               class="el-menu-item flex-grow-1 w-100"
                            >Доступы</a>
                        </el-menu-item>
                        <el-menu-item index="/management/department" text-color="#fff"
                                      v-if="can('department.indexWeb')"
                                      class="px-0">
                            <a href="/management/department" class="el-menu-item flex-grow-1 w-100">Департаменты</a>
                        </el-menu-item>
                        <el-menu-item index="/management/division" text-color="#fff" class="px-0"
                                      v-if="can('division.indexWeb')">
                            <a href="/management/division" class="el-menu-item flex-grow-1 w-100">Отделы</a>
                        </el-menu-item>
                        <el-menu-item index="/management/position" text-color="#fff" class="px-0"
                                      v-if="can('position.indexWeb')">
                            <a href="/management/position" class="el-menu-item flex-grow-1 w-100">Должности</a>
                        </el-menu-item>
                        <el-menu-item index="/management/grade" text-color="#fff" class="px-0">
                            <a href="/management/grade" class="el-menu-item flex-grow-1 w-100">Уровни</a>
                        </el-menu-item>
                        <el-menu-item index="/management/device" text-color="#fff"
                                      class="px-0">
                            <a href="/management/device" class="el-menu-item flex-grow-1 w-100">Гаджеты</a>
                        </el-menu-item>
                    </el-sub-menu>
                    <el-sub-menu index="booking">
                        <template #title>
                            <el-icon>
                                <em class="fas fa-calendar"></em>
                            </el-icon>
                            <span>Бронирование</span>
                        </template>
                        <el-menu-item index="/booking" text-color="#fff" class="px-0">
                            <a href="/booking" class="el-menu-item flex-grow-1 w-100">Бронирование на сегодня</a>
                        </el-menu-item>
                        <el-menu-item index="/booking-report" text-color="#fff" class="px-0">
                            <a href="/booking-report" class="el-menu-item flex-grow-1 w-100">Рапорт</a>
                        </el-menu-item>
                        <el-menu-item index="/room" text-color="#fff" v-if="is('admin_company')" class="px-0" >
                            <a href="/room" class="el-menu-item flex-grow-1 w-100">Комнаты</a>
                        </el-menu-item>
                        <el-menu-item index="/service" text-color="#fff" class="px-0" >
                            <a href="/service" class="el-menu-item flex-grow-1 w-100">Обслуживание комнат</a>
                        </el-menu-item>
                    </el-sub-menu>
                    <el-sub-menu index="/visitor"
                                 v-if="can('guest.indexWeb|guest.indexWeb')
                                 || is('super_admin')">
                        <template #title>
                            <el-icon>
                                <em class="fas fa-ticket-alt"></em>
                            </el-icon>
                            <span>Визитор</span>
                        </template>
                        <el-menu-item index="/visitor/guest" class="px-0" v-if="can('guest.indexWeb')">
                            <a href="/visitor/guest" class="el-menu-item flex-grow-1 w-100">Гости</a>
                        </el-menu-item>
                        <el-menu-item index="/visitor/invite" class="px-0" v-if="can('invite.indexWeb')">
                            <a href="/visitor/invite" class="el-menu-item flex-grow-1 w-100">Приглашения</a>
                        </el-menu-item>
                    </el-sub-menu>
                    <el-sub-menu index="/admin"
                                 v-if="can('user.indexWeb|company.indexWeb|floor.indexWeb|role.indexWeb|indexWeb|terminal.indexWeb|zone.indexWeb')
                                 || is('super_admin')">
                        <template #title>
                            <el-icon>
                                <em class="fas fa-user-secret"></em>
                            </el-icon>
                            <span>Администрирование</span>
                        </template>
                        <el-menu-item index="/admin/user" class="px-0" v-if="can('user.indexWeb')">
                            <a href="/admin/user" class="el-menu-item flex-grow-1 w-100">Пользователи</a>
                        </el-menu-item>
                        <el-menu-item index="/admin/company" text-color="#fff" class="px-0"
                                      v-if="can('company.indexWeb')">
                            <a href="/admin/company" class="el-menu-item flex-grow-1 w-100">Компании</a>
                        </el-menu-item>
                        <el-menu-item index="/admin/floor" text-color="#fff" class="px-0" v-if="can('floor.indexWeb')">
                            <a href="/admin/floor" class="el-menu-item flex-grow-1 w-100">Этажы</a>
                        </el-menu-item>

                        <el-menu-item index="/admin/role" text-color="#fff" class="px-0" v-if="can('role.indexWeb')">
                            <a href="/admin/role" class="el-menu-item flex-grow-1 w-100">Роли</a>
                        </el-menu-item>
                        <el-menu-item index="/admin/permission" text-color="#fff" class="px-0"
                                      v-if="can('permission.indexWeb')">
                            <a href="/admin/permission" class="el-menu-item flex-grow-1 w-100">Права</a>
                        </el-menu-item>
                        <el-menu-item index="/admin/terminal" text-color="#fff" class="px-0"
                                      v-if="can('terminal.indexWeb')">
                            <a href="/admin/terminal" class="el-menu-item flex-grow-1 w-100">Устройства</a>
                        </el-menu-item>
                        <el-menu-item index="/admin/zone" text-color="#fff" class="px-0" v-if="can('zone.indexWeb')">
                            <a href="/admin/zone" class="el-menu-item flex-grow-1 w-100">Зоны</a>
                        </el-menu-item>
                        <el-menu-item index="/admin/logs" text-color="#fff" class="px-0"
                                      v-if="can('terminal.indexWeb')">
                            <a href="/admin/logs" class="el-menu-item flex-grow-1 w-100">Лог устройств</a>
                        </el-menu-item>
                    </el-sub-menu>


                </el-menu>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>

<script setup>

const props = defineProps({
    prefix: String,
    authUser: Object,
    currentPath: String
})


</script>

<style>
.el-menu-item.is-active > a {
    color: var(--el-menu-active-color);
}
</style>
