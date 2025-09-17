<template>
    <div class="container mx-auto p-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Welcome to {{ projectName }}!
            </h1>
            <p class="text-lg text-gray-600 mb-6">
                {{ description }}
            </p>
            <div class="flex justify-center space-x-4">
                <a href="https://laravel.com/docs" 
                   class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-colors"
                   target="_blank">
                    Laravel Documentation
                </a>
                <a href="https://vuejs.org/guide/" 
                   class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition-colors"
                   target="_blank">
                    Vue.js Guide
                </a>
            </div>
            <div class="mt-8 p-4 bg-gray-100 rounded">
                <h3 class="text-xl font-semibold mb-2">Counter Example</h3>
                <p class="text-gray-700 mb-4">Current count: <span class="font-bold text-blue-600">{{ count }}</span></p>
                <div class="space-x-2">
                    <button @click="increment" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                        +1
                    </button>
                    <button @click="decrement" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                        -1
                    </button>
                    <button @click="reset" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition-colors">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

// Reactive data
const projectName = ref('MPET')
const description = ref("Mario's Pet Project - Laravel with Vue Starter Kit")
const count = ref(0)

// Methods
const increment = () => {
    count.value++
}

const decrement = () => {
    count.value--
}

const reset = () => {
    count.value = 0
}
</script>

<style scoped>
/* Any component-specific styles can go here */
</style>