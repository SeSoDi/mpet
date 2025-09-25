<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { Input } from '@/components/ui/input'
import { cn } from '@/lib/utils'

interface Props {
  modelValue?: number | string
  disabled?: boolean
  placeholder?: string
  currency?: string
  locale?: string
  class?: string
  id?: string
  min?: number
  max?: number
  step?: number
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: 0,
  disabled: false,
  placeholder: '0.00',
  currency: 'MXN',
  locale: 'es-MX',
  class: '',
  id: '',
  min: 0,
  step: 0.01,
})

const emit = defineEmits<{
  'update:modelValue': [value: number]
}>()

const isFocused = ref(false)





// Format number to currency string for display
const formatCurrency = (value: number): string => {
  if (isNaN(value) || value === 0) return ''
  
  return new Intl.NumberFormat(props.locale, {
    style: 'currency',
    currency: 'MXN',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(value)
}

// Format number with thousand separators (no currency symbol)
const formatWithSeparators = (value: number): string => {
  if (isNaN(value) || value === 0) return ''
  
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(value)
}



// Parse currency string to number
const parseCurrency = (value: string): number => {
  if (!value || value.trim() === '') return 0
  
  // Remove currency symbols, thousand separators, and spaces, but keep decimal points and minus signs
  const cleanValue = value
    .replace(/[$MXN\s]/g, '') // Remove currency symbols and spaces
    .replace(/,/g, '') // Remove thousand separators
    .replace(/[^\d.-]/g, '') // Keep only digits, decimal point, and minus sign
  
  const parsed = parseFloat(cleanValue)
  return isNaN(parsed) ? 0 : parsed
}

// Convert input to number, handling both string and number types
const numericValue = computed(() => {
  if (typeof props.modelValue === 'number') {
    return props.modelValue
  }
  if (typeof props.modelValue === 'string') {
    const parsed = parseCurrency(props.modelValue)
    return parsed
  }
  return 0
})

// Computed value for the input
const inputValue = computed({
  get: () => {
    if (isFocused.value) {
      // When focused, show raw number for editing
      return numericValue.value > 0 ? numericValue.value.toString() : ''
    } else {
      // When not focused, show formatted value
      return numericValue.value > 0 ? formatWithSeparators(numericValue.value) : ''
    }
  },
  set: (value: string) => {
    const parsed = parseCurrency(value)
    emit('update:modelValue', parsed)
  }
})



const handleFocus = () => {
  isFocused.value = true
}

const handleBlur = () => {
  isFocused.value = false
}

const handleKeydown = (event: KeyboardEvent) => {
  // Allow: backspace, delete, tab, escape, enter, arrow keys
  const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End']
  if (allowedKeys.includes(event.key)) return
  
  // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X, Ctrl+Z
  if (event.ctrlKey && ['a', 'c', 'v', 'x', 'z'].includes(event.key.toLowerCase())) return
  
  // Allow: numbers, decimal point, and comma (thousand separator)
  if (/^[0-9.,]$/.test(event.key)) {
    const currentValue = (event.target as HTMLInputElement).value
    
    // Only one decimal point allowed
    if (event.key === '.' && currentValue.includes('.')) {
      event.preventDefault()
      return
    }
    
    return
  }
  
  // Block all other keys
  event.preventDefault()
}

// No longer need formatted preview since we format directly in input
</script>

<template>
  <div class="relative">
    <!-- Debug info -->
    <div v-if="false" class="text-xs text-red-500 mb-1">
      Debug: modelValue={{ props.modelValue }}, inputValue={{ inputValue }}, disabled={{ props.disabled }}
    </div>
    
    <Input
      :id="props.id"
      v-model="inputValue"
      :disabled="props.disabled"
      :placeholder="props.placeholder"
      :class="cn('pl-8', props.class)"
      :min="props.min"
      :max="props.max"
      :step="props.step"
      type="text"
      inputmode="decimal"
      @focus="handleFocus"
      @blur="handleBlur"
      @keydown="handleKeydown"
    />
    
    <!-- Currency symbol -->
    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none">
      $
    </span>
  </div>
</template>