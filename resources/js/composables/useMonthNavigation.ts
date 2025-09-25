import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

export interface UseMonthNavigationOptions {
  route: string // The base route (e.g., '/monthly-sales', '/monthly-billing')
  initialYear?: number
  initialMonth?: number
  additionalParams?: Record<string, any> // For extra params like unidad
  withoutProtection?: (callback: () => void) => void // Optional function to wrap navigation calls
}

export function useMonthNavigation(options: UseMonthNavigationOptions) {
  const { route, initialYear, initialMonth, additionalParams = {}, withoutProtection } = options

  // State
  const selectedYear = ref(initialYear || new Date().getFullYear())
  const selectedMonth = ref(initialMonth || new Date().getMonth() + 1)

  // Month names
  const monthNames = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
  ]

  // Computed
  const currentMonthName = computed(() => {
    return monthNames[selectedMonth.value - 1] || selectedMonth.value.toString()
  })

  // Navigation functions
  const navigateMonth = (direction: 'prev' | 'next') => {
    let newYear = selectedYear.value
    let newMonth = selectedMonth.value

    if (direction === 'prev') {
      newMonth--
      if (newMonth < 1) {
        newMonth = 12
        newYear--
      }
    } else {
      newMonth++
      if (newMonth > 12) {
        newMonth = 1
        newYear++
      }
    }

    selectedYear.value = newYear
    selectedMonth.value = newMonth

    // Prepare parameters
    const params = {
      year: newYear,
      month: newMonth,
      ...additionalParams
    }

    // Navigate with or without protection
    const navigate = () => {
      router.get(route, params)
    }

    if (withoutProtection) {
      withoutProtection(navigate)
    } else {
      navigate()
    }
  }

  const goToCurrentMonth = () => {
    const now = new Date()
    selectedYear.value = now.getFullYear()
    selectedMonth.value = now.getMonth() + 1

    // Prepare parameters
    const params = {
      year: selectedYear.value,
      month: selectedMonth.value,
      ...additionalParams
    }

    // Navigate with or without protection
    const navigate = () => {
      router.get(route, params)
    }

    if (withoutProtection) {
      withoutProtection(navigate)
    } else {
      navigate()
    }
  }

  const getMonthName = (month: number) => {
    return monthNames[month - 1] || month.toString()
  }

  // Update additional parameters (useful for things like changing unit)
  const updateAdditionalParams = (newParams: Record<string, any>) => {
    Object.assign(additionalParams, newParams)
  }

  return {
    selectedYear,
    selectedMonth,
    currentMonthName,
    navigateMonth,
    goToCurrentMonth,
    getMonthName,
    updateAdditionalParams,
  }
}