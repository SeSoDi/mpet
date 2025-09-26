import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import type { User, Permission } from '@/types'

/**
 * Composable for checking user permissions and roles
 */
export function usePermissions() {
    const page = usePage()
    
    const user = computed(() => page.props.auth?.user as User | undefined)
    
    const roles = computed(() => user.value?.roles || [])
    
    const permissions = computed(() => {
        if (!user.value?.roles) return []
        
        const allPermissions: Permission[] = []
        for (const role of user.value.roles) {
            if (role.permissions) {
                allPermissions.push(...role.permissions)
            }
        }
        
        // Remove duplicates by id
        return allPermissions.filter((permission, index, self) => 
            index === self.findIndex(p => p.id === permission.id)
        )
    })
    
    /**
     * Check if user has a specific role
     */
    const hasRole = (roleName: string): boolean => {
        if (!user.value) return false
        return roles.value.some(role => role.name === roleName)
    }
    
    /**
     * Check if user has any of the given roles
     */
    const hasAnyRole = (roleNames: string[]): boolean => {
        if (!user.value) return false
        return roleNames.some(roleName => hasRole(roleName))
    }
    
    /**
     * Check if user has all of the given roles
     */
    const hasAllRoles = (roleNames: string[]): boolean => {
        if (!user.value) return false
        return roleNames.every(roleName => hasRole(roleName))
    }
    
    /**
     * Check if user has a specific permission
     */
    const hasPermission = (permissionName: string): boolean => {
        if (!user.value) return false
        
        // SuperAdmin always has all permissions
        if (isSuperAdmin.value) return true
        
        return permissions.value.some(permission => permission.name === permissionName)
    }
    
    /**
     * Check if user has any of the given permissions
     */
    const hasAnyPermission = (permissionNames: string[]): boolean => {
        if (!user.value) return false
        
        // SuperAdmin always has all permissions
        if (isSuperAdmin.value) return true
        
        return permissionNames.some(permissionName => hasPermission(permissionName))
    }
    
    /**
     * Check if user has all of the given permissions
     */
    const hasAllPermissions = (permissionNames: string[]): boolean => {
        if (!user.value) return false
        
        // SuperAdmin always has all permissions
        if (isSuperAdmin.value) return true
        
        return permissionNames.every(permissionName => hasPermission(permissionName))
    }
    
    /**
     * Check if user is SuperAdmin
     */
    const isSuperAdmin = computed(() => hasRole('superadmin'))
    
    /**
     * Check if user is Admin (admin or superadmin)
     */
    const isAdmin = computed(() => hasAnyRole(['admin', 'superadmin']))
    
    /**
     * Get user's role names as array
     */
    const roleNames = computed(() => roles.value.map(role => role.name))
    
    /**
     * Get user's permission names as array
     */
    const permissionNames = computed(() => permissions.value.map(permission => permission.name))
    
    /**
     * Check if user can access users management
     */
    const canManageUsers = computed(() => 
        hasAnyPermission(['view_users', 'create_users', 'edit_users', 'delete_users'])
    )
    
    /**
     * Check if user can access roles management
     */
    const canManageRoles = computed(() => 
        hasAnyPermission(['view_roles', 'manage_roles'])
    )
    
    /**
     * Check if user can view system logs
     */
    const canViewLogs = computed(() => hasPermission('view_logs'))
    
    /**
     * Check if user can manage system settings
     */
    const canManageSettings = computed(() => hasPermission('manage_settings'))
    
    return {
        user,
        roles,
        permissions,
        roleNames,
        permissionNames,
        hasRole,
        hasAnyRole,
        hasAllRoles,
        hasPermission,
        hasAnyPermission,
        hasAllPermissions,
        isSuperAdmin,
        isAdmin,
        canManageUsers,
        canManageRoles,
        canViewLogs,
        canManageSettings,
    }
}