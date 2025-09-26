import Auth from './Auth'
import UserController from './UserController'
import RoleController from './RoleController'
import PermissionController from './PermissionController'
import LogController from './LogController'
import VentaMensualController from './VentaMensualController'
import UnidadController from './UnidadController'
import FacturacionMensualController from './FacturacionMensualController'
import Settings from './Settings'

const Controllers = {
    Auth: Object.assign(Auth, Auth),
    UserController: Object.assign(UserController, UserController),
    RoleController: Object.assign(RoleController, RoleController),
    PermissionController: Object.assign(PermissionController, PermissionController),
    LogController: Object.assign(LogController, LogController),
    VentaMensualController: Object.assign(VentaMensualController, VentaMensualController),
    UnidadController: Object.assign(UnidadController, UnidadController),
    FacturacionMensualController: Object.assign(FacturacionMensualController, FacturacionMensualController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers