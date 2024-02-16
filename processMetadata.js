const functions = require("firebase-functions");
const admin = require("firebase-admin");
admin.initializeApp();

exports.processFileMetadata = functions.storage.object().onFinalize(async (object) => {
  // Access the file metadata
  const bucket = admin.storage().bucket(object.bucket);
  const file = bucket.file(object.name);
  const [metadata] = await file.getMetadata();

  // You can now access metadata properties like name, size, contentType, etc.
  const { name, size, contentType } = metadata;

  // Store metadata in Firestore or perform other actions here
  const database = admin.firestore();
  const metadataRef = database.collection("fileMetadata").doc(object.name);
  await metadataRef.set({
    name,
    size,
    contentType,
    // Add more metadata properties here as needed
  });

  // You can also trigger other actions based on the metadata
  // For example, you can send a notification or update a record in your database

  // Logging for debugging
  console.log(`Metadata for file ${object.name}:`, metadata);

  return null;
});
