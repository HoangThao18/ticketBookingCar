// Tạo cho tôi một chuỗi mã hoá base 64
// https://www.codewars.com/kata/create-a-base-64-encoded-string/train/javascript
function encodeBase64(str) {
    return btoa(unescape(encodeURIComponent(str)));
}
var encodedString = encodeBase64("70762370");
console.log(encodedString);

// Chuỗi đã được mã hóa Base64
// var encodedString = "2PfjHxukeyUGu0GyjU8Q0FZs7cogBcV9q3IZ8rYoSIK5syudu9dLcph1aZClbaRhgkARBjnhShXWhQeRtRfQXKYS6im13QyUTBhl5YpF7g8zsbsoDki3rYcinqvfIWf1MHb5GdCus8mCkJrdS/YFSm9pHglhEmRD34UxSKJeFg8=";
// encodedString = encodeBase64("Hello World!");

// Giải mã chuỗi
var decodedString = atob(encodedString);

// // In ra chuỗi giải mã
console.log(decodedString);
