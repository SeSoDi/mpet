import { ref, computed, onMounted, onUnmounted, unref, isRef, type MaybeRef } from 'vue'
import { router } from '@inertiajs/vue3'

export interface UnsavedChangesOptions {
  message?: string
  exclude?: string[]
}

/**
 * Composable for tracking unsaved changes in forms and warning users before navigation
 * 
 * @param originalData - The original/initial form data to compare against
 * @param currentData - The current form data (reactive)
 * @param options - Configuration options
 */
export function useUnsavedChanges<T extends Record<string, any>>(
  originalData: MaybeRef<T>,
  currentData: MaybeRef<T>,
  options: UnsavedChangesOptions = {}
) {
  const {
    message = '¿Estás seguro de que quieres salir? Los cambios no guardados se perderán.',
    exclude = []
  } = options

  const isEnabled = ref(true)
  const hasUnsavedChanges = computed(() => {
    if (!isEnabled.value) return false

    const original = unref(originalData)
    const current = unref(currentData)

    // Compare each property between original and current data
    for (const key in current) {
      if (exclude.includes(key)) continue
      
      if (current[key] !== original[key]) {
        return true
      }
    }
    return false
  })

  // Browser beforeunload protection
  const handleBeforeUnload = (event: BeforeUnloadEvent) => {
    if (hasUnsavedChanges.value) {
      event.preventDefault()
      event.returnValue = message
      return message
    }
  }

  // Inertia navigation protection
  let removeInertiaListener: (() => void) | null = null

  const setupInertiaProtection = () => {
    removeInertiaListener = router.on('before', (event) => {
      if (hasUnsavedChanges.value && isEnabled.value) {
        // Only show confirm dialog for navigation away from current route
        if (!confirm(message)) {
          event.preventDefault()
          return false
        }
      }
    })
  }

  // Enable/disable protection
  const enable = () => {
    isEnabled.value = true
  }

  const disable = () => {
    isEnabled.value = false
  }

  // Temporarily disable protection (useful when saving)
  const withoutProtection = async <R>(callback: () => Promise<R> | R): Promise<R> => {
    disable()
    try {
      return await callback()
    } finally {
      enable()
    }
  }

  // Reset original data (useful after successful save)
  const resetOriginalData = (newData: T) => {
    if (isRef(originalData)) {
      Object.assign(originalData.value, newData)
    } else {
      Object.assign(originalData, newData)
    }
  }

  // Setup and cleanup
  onMounted(() => {
    window.addEventListener('beforeunload', handleBeforeUnload)
    setupInertiaProtection()
  })

  onUnmounted(() => {
    window.removeEventListener('beforeunload', handleBeforeUnload)
    if (removeInertiaListener) {
      removeInertiaListener()
    }
  })

  return {
    hasUnsavedChanges: computed(() => hasUnsavedChanges.value),
    isEnabled: computed(() => isEnabled.value),
    enable,
    disable,
    withoutProtection,
    resetOriginalData
  }
}