function edit(id) {
    // 編集対象の各フィールドの<span>と<input>、<select>を取得
    var fields = ['diary_name', 'diary_containts', 'diary_day', 'spot', 'people', 'cost', 'category_id'];

    fields.forEach(function (field) {
        var spanElement = document.getElementById(field + '_' + id);
   
        if (spanElement) {
            var inputValue = spanElement.innerText;
            var inputElement = document.querySelector('input[name="' + field + '"][value="' + inputValue + '"]');
            var selectElement = document.querySelector('select[name="' + field + '"][id="' + field + '_' + id + '"]');

            if (inputElement) {
                // 表示を切り替える
                if (spanElement.style.display === 'none' || spanElement.style.display === '') {
                    spanElement.style.display = 'inline';
                    inputElement.style.display = 'none';
                } else {
                    spanElement.style.display = 'none';
                    inputElement.style.display = 'inline';
                }
            } else if (selectElement) {
                // セレクトボックスの選択された値を取得
                // var selectedValue = selectElement.value;
                // console.log('Selected value for ' + field + ':', selectedValue);

                // 表示を切り替える
                if (spanElement.style.display === 'none' || spanElement.style.display === '') {
                    spanElement.style.display = 'inline';
                    selectElement.style.display = 'none';
                } else {
                    spanElement.style.display = 'none';
                    selectElement.style.display = 'inline';
                }
            }
        }
    });
}
