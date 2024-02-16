<template>
    <div class="flex min-h-screen flex-col lg:flex-row relative">
      <div class="lg:w-2/3 h-80 lg:h-screen hidden sm:block relative">
        <div class="h-full w-full object-cover lg:object-fill filter brightness-50">
          <img class="h-full w-full" src="/images/univ.png" alt="bg-dpt" />
        </div>

        <div class="absolute top-1/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 mt-16">
          <img class="w-80 h-80" src="/images/ccelogo.png" alt="school icon" />
        </div>
  
        <div class="absolute top-2/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
          <p class="text-white text-4xl font-bold">CCEtracker</p>
          <p class="text-white text-2xl font-medium">Web-based Document Management System for the College of Computing Education with Data Visualization</p>
        </div>
      </div>
  
      <div class="lg:w-1/3 flex justify-center items-center min-h-screen">
        <router-view></router-view>
      </div>
    </div>
  </template>
  
  <script setup>
  </script>
  
  <style scoped>
  </style>
  