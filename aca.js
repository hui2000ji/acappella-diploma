let verify = function() {
    let groupInput = document.getElementById('input-group-button')
    if (groupInput.value) {
        // let groups = ['B-One', 'Paca', 'iScream', 'SEAbling', 'Wake-UP', 'Acatrue', 'Sunday昊', 'Vocal Booth']
        let groups_encrypted = ["U2FsdGVkX18CE858Elt6AH7ncGPBMKz0C7LXXYwd+i8=", "U2FsdGVkX19LfD62aP0OY2kDuPTjvZQD2oQ+PDVzpwU=", "U2FsdGVkX1/zdqeh77he6HHJ1bzQM1OnSeTYc5cSXFA=", "U2FsdGVkX1/lvcXye+Em40AgXWks/YYK0MJpfSdFRx8=", "U2FsdGVkX1/CAMq4Fy5RXVPEZ1hE8ypQilDRB6ZRMk8=", "U2FsdGVkX18mfuHm1wtDzLlO+YQAqXgiPb3xZCbRDps=", "U2FsdGVkX1+bl+DVEOk0uJtYz955P+VC1nLPlEf7Jpk=", "U2FsdGVkX188iQlv8L0Zud0m1JmHsf/CdtjhXxYWcbU="]
        let key = CryptoJS.AES.decrypt("U2FsdGVkX1+SU7WPgLCNqJraEN+IiENV4gJ5xzML7VhaIoBTTEHJCWRrVVDle778", "我爱皮老师").toString(CryptoJS.enc.Utf8)
        let groups = groups_encrypted.map((val, i, arr) => CryptoJS.AES.decrypt(val, key).toString(CryptoJS.enc.Utf8))
        if (groups.includes(groupInput.value)) {
            let verifyElem = document.getElementById('verify')
            let contentElem = document.getElementById('content')
            verifyElem.style.display = "none"
            contentElem.style.display = ""
        }
    }
}

let getClearedCtx = function() {
    let canvas = document.getElementById('canvas')
    let ctx = canvas.getContext('2d')
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    return {canvas, ctx}
}

let showCanvas = function (canvas) {
    let targetImgURL = canvas.toDataURL()
    targetImg = document.getElementById('final-image')
    targetImg.src = targetImgURL
}

let drawCanvas = function() {
    let {canvas, ctx} = getClearedCtx()

    let backgroundImg = document.getElementById('background-img')
    ctx.drawImage(backgroundImg, 0, 0, 3780, 2598)

    textInput = document.getElementById('input-name-button')
    ctx.font = '80px STSong';
    ctx.textAlign = 'center'
    ctx.fillText(textInput.value, 760, 1275)

    birthInput = document.getElementById('input-birth-button')
    if (birthInput.value) {
        birth = new Date(birthInput.value)
        ctx.font = '80px STSong';
        ctx.fillText("" + birth.getFullYear(), 1145, 1275)
        ctx.fillText("" + (birth.getMonth() + 1), 1428, 1275)
        ctx.fillText("" + birth.getDate(), 1685, 1275)
    }

    photoInput = document.getElementById('input-photo-button')
    if (photoInput.value) {
        let img = new Image();
        img.src = URL.createObjectURL(photoInput.files[0]);
        img.addEventListener('load', function() {
            ctx.drawImage(img, 2885, 1060, 569, 758)
            showCanvas(canvas)
        }, false);
    }

    yearInput = document.getElementById('input-year-button')
    if (yearInput.value && yearInput.value <= 2020 && yearInput.value >= 2011 && !yearInput.value.includes('.')) {
        let signature = document.getElementById('signature-' + yearInput.value)
        ctx.drawImage(signature, 2110, 1960)
    }

    showCanvas(canvas)
}

window.onload = e => drawCanvas()