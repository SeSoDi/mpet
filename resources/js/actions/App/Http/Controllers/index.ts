import Auth from './Auth'
import UserController from './UserController'
import RoleController from './RoleController'
import PermissionController from './PermissionController'
import LogController from './LogController'
import Settings from './Settings'

const Controllers = {
    Auth: Object.assign(Auth, Auth),
    UserController: Object.assign(UserController, UserController),
    RoleController: Object.assign(RoleController, RoleController),
    PermissionController: Object.assign(PermissionController, PermissionController),
    LogController: Object.assign(LogController, LogController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers