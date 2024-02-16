<template>
    <div class="flex items-center justify-center h-screen">
      <div class="text-center">
        <h1 class="text-6xl font-bold text-red-600">404</h1>
        <p class="mt-4 text-lg text-gray-600">Page not found</p>
        <router-link to="/" class="mt-8 inline-block px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">Go Home</router-link>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'NotFound',
  };
  </script>