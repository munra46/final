// document.addEventListener("DOMContentLoaded", function() {
//     selectedUrls = ''; 
// });
// var click = ''; 


// function searchAndSetSpot() {
//     document.forms["diaryForm"]["search_button"].value = "1";
//     document.forms["diaryForm"].submit();
// }

// // 場所を設定する関数
// function setSpot(shopName) {
//     var spotElement = document.getElementById("spot");
//     if (spotElement) {
//         spotElement.value = shopName;
//     } else {
//         console.error("Element with ID 'spot' not found");
//     }
// }

// function hideSearchResults() {
//     var searchResults = document.getElementById("searchResults");
//     if (searchResults) {
//         searchResults.style.display = "none";
//     }
// }


// // var selectedName = ''; // 名前を保存する変数
// // var selectedUrls = ''; // URLを保存する変数


// function setSpotAndHideResults(spot) {
//     // 名前とURLを分割
//     var parts = spot.split('|');
//     var selectedName = parts[0];
//     var selectedUrls = parts[1];

//     // hidden フィールドの値を取得
//     var hiddenName = document.querySelector('input[name="selectedShopNames"]');
//     var hiddenUrls = document.querySelector('input[name="selectedUrls"]');

//     // hidden フィールドに値を設定
//     hiddenName.value = selectedName;
//     hiddenUrls.value = selectedUrls;

//     // クリックされた店舗名を変数に設定
//     click = selectedName;
//     console.log(click);
//     console.log(hiddenName.value);
//     console.log(hiddenUrls.value);
//     setSpot(selectedName);
//     // 検索結果を非表示にする
//     hideSearchResults();
//     // displaySelectedShop()
// }

// // function displaySelectedShop() {
// //     var searchResults = document.getElementById("searchResults");
// //     if (searchResults) {
// //         var shops = searchResults.getElementsByTagName("p");
// //         for (var i = 0; i < shops.length; i++) {
// //             if (shops[i].innerText === click) {
// //                 shops[i].style.display = "block";
// //             } else {
// //                 shops[i].style.display = "none";
// //             }
// //         }
// //     }
// // }

    var selectedUrls = '';
    var click = '';
    var clickedOnce = false;


function searchAndSetSpot() {
    document.forms["diaryForm"]["search_button"].value = "1";
    document.forms["diaryForm"].submit();
}

function setSpot(shopName) {
    var spotElement = document.getElementById("spot");
    if (spotElement) {
        spotElement.value = shopName;
    } else {
        console.error("Element with ID 'spot' not found");
    }
}

function hideSearchResults() {
    var searchResults = document.getElementById("searchResults");
    if (searchResults) {
        searchResults.style.display = "none";
    }
}

function setSpotAndHideResults(spot) {
    // 名前とURLを分割
    var parts = spot.split('|');
    var selectedName = parts[0];
    var selectedUrls = parts[1];

    // hidden フィールドの値を取得
    var hiddenName = document.querySelector('input[name="selectedShopNames"]');
    var hiddenUrls = document.querySelector('input[name="selectedUrls"]');

    // hidden フィールドに値を設定
    hiddenName.value = selectedName;
    hiddenUrls.value = selectedUrls;

    // クリックされた店舗名を変数に設定
    click = selectedName;
    console.log(click);
    console.log(hiddenName.value);
    console.log(hiddenUrls.value);
    setSpot(selectedName);
    // 検索結果を非表示にする
    hideSearchResults();

    // 検索結果が一つ以上ある場合にメッセージを表示
    var searchResults = document.getElementById("searchResults");
    var shops = searchResults.getElementsByTagName("p");
    var messageElement = document.getElementById("clickMessage");

    if (shops.length > 3) {
        messageElement.innerText = "もう一度検索を押しクリックしてください";
        messageElement.style.color = "red";
    } else {
        messageElement.innerText = ""; // メッセージをクリア
    }
}
