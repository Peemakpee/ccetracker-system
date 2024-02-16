import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";

const firebaseConfig = {
    apiKey: "AIzaSyCqwGohMbtJUFenWu7bdmtnWt653BYkAdU",
    authDomain: "ccetracker.firebaseapp.com",
    projectId: "ccetracker",
    storageBucket: "ccetracker.appspot.com",
    messagingSenderId: "699760839322",
    appId: "1:699760839322:web:4819bdb9cc2737494bed23",
    measurementId: "G-X0C969TTDB"
};

const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
import { getStorage } from "firebase/storage";
const storage = getStorage(app);

export { storage }