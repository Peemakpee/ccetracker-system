<template>
    <div class="p-5">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-40">
        <h1 class="text-xl font-medium tracking-tight text-gray-900">
          CCE Dean’s Office Document Management System
        </h1>
      </div>
    </div>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="text-3xl font-bold sm:px-6 lg:px-40 sm:block">
          Policies Standards and Guidelines (PSG)
        </div>
        <div class="ml-40 py-1 border-b-2 w-full border-black sm:block"></div>
        <br>
        <div class="ml-5 sm:ml-40">
          <div class="py-1 text-md text-center font-bold rounded-t-lg" style="background-color: #CFAE46; opacity: 1">
            Available PSG Templates
          </div>
          <div class="overflow-x-auto">

            <div v-if="loading" class="flex justify-center py-4">
              <div class="loader ease-linear rounded-full border-4 border-t-4 border-yellow-500 h-12 w-12"></div>
            </div>
  
            <div v-else-if="files.length === 0" class="text-center py-4">
              No files uploaded
            </div>
  
            <table v-else class="min-w-full bg-white rounded-b-xl overflow-hidden">
              <thead class="bg-gray-100 border-b">
                <tr>
                  <th class="py-2 px-4 text-left">File Name</th>
                  <th class="py-2 px-4 text-left">Download</th>
                  <th class="py-2 px-4 text-left">Size</th>
                  <th class="py-2 px-4 text-left">Type</th>
                  <th class="py-2 px-4 text-left">Date & Time Uploaded</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="file in files" :key="file.name">
                  <td class="py-2 px-4">{{ getFileNameWithoutExtension(file.name) }}</td>
                  <td class="py-2 px-4">
                    <a :href="file.url" target="_blank" class="text-blue-500 hover:underline">Download</a>
                  </td>
                  <td class="py-2 px-4">{{ formatSize(file.size) }}</td>
                  <td class="py-2 px-4">{{ getFileExtension(file.type) }}</td>
                  <td class="py-2 px-4">{{ formatDate(file.uploadTime) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { storage } from "../../../firebase";
  import { ref, listAll, getDownloadURL, getMetadata } from "firebase/storage";
  
  export default {
    data() {
      return {
        files: [],
        loading: true, 
      };
    },
    created() {
      this.listFiles();
    },
    methods: {
      async listFiles() {
        const folderRef = ref(storage, 'deliverables_template/PSG');
  
        try {
          const items = await listAll(folderRef);
  
          const filePromises = items.items.map(async (item) => {
            const url = await getDownloadURL(item);
            const metadata = await getMetadata(item);
            return {
              name: item.name,
              url,
              size: metadata.size,
              type: metadata.contentType,
              uploadTime: metadata.timeCreated, 
            };
          });
  
          const downloadURLs = await Promise.all(filePromises);
  
          this.files = downloadURLs;
          this.loading = false; 
        } catch (error) {
          console.error("Error listing files:", error);
        }
      },
      formatSize(size) {

        const sizes = ["Bytes", "KB", "MB", "GB", "TB"];
        if (size === 0) return "0 Byte";
        const i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
        return Math.round(size / Math.pow(1024, i), 2) + " " + sizes[i];
      },
      getFileExtension(contentType) {

        const extensions = {
          'application/pdf': 'pdf',
          'application/msword': 'doc',
          'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'docx',
        };
        return extensions[contentType] || 'Unknown';
      },
      getFileNameWithoutExtension(fullFileName) {
        return fullFileName.replace(/\.[^/.]+$/, "");
      },
      formatDate(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleString();
      },
    },
  };
  </script>
  
  <style>
  .loader {
    border-top-color: green;
    border-left-color: green;
    animation: spin 1.5s linear infinite;
  }
  
  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }
  
    100% {
      transform: rotate(360deg);
    }
  }
  </style>
  