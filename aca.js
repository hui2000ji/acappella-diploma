"use strict"

let getClearedCtx = function() {
    let canvas = document.getElementById('canvas')
    let ctx = canvas.getContext('2d')
    ctx.clearRect(0, 0, canvas.width, canvas.height)
    return {canvas, ctx}
}

let showCanvas = function (canvas) {
    canvas.toBlob(function(blob) {
        let targetImg = document.getElementById('final-image')
        targetImg.src = URL.createObjectURL(blob)
    })
}

let drawCanvas = function() {
    let {canvas, ctx} = getClearedCtx()
    let progress = 0
    let deferPainting = false

    let backgroundImg = document.getElementById('background-img')
    ctx.drawImage(backgroundImg, 0, 0, 3780, 2598)

    let nameInput = document.getElementById('input-name-button')
    if (nameInput.value) {
        progress += 25
        ctx.font = '76px STSong, Noto Serif SC';
        ctx.textAlign = 'center'
        ctx.fillText(nameInput.value, 760, 1270)
    }

    let birthInput = document.getElementById('input-birth-button')
    if (birthInput.value) {
        progress += 25
        let birth = new Date(birthInput.value)
        ctx.font = '76px STSong, Noto Serif SC';
        ctx.textAlign = 'center'
        ctx.fillText("" + birth.getFullYear(), 1145, 1270)
        ctx.fillText("" + (birth.getMonth() + 1), 1428, 1270)
        ctx.fillText("" + birth.getDate(), 1685, 1270)
    }
    
    let yearInput = document.getElementById('input-year-button')
    if (yearInput.value && yearInput.value <= 2019 && yearInput.value >= 2010) {
        progress += 25
        let signature = document.getElementById('signature-' + yearInput.value)
        ctx.drawImage(signature, 1700, 1920, 525, 393)
        ctx.font = '76px STSong, Noto Serif SC';
        ctx.textAlign = 'center'
        let roman2sc = {
            "0": "〇",
            "1": "一",
            "2": "二",
            "3": "三",
            "4": "四",
            "5": "五",
            "6": "六",
            "7": "七",
            "8": "八",
            "9": "九"
        }
        ctx.fillText(yearInput.value.split("").map(roman => roman2sc[roman]).join(''), 2730, 2265)
        ctx.fillText("七", 3085, 2265)
        ctx.fillText("一", 3305, 2265)
    }

    let photoInput = document.getElementById('input-photo-button')
    if (photoInput.value) {
        progress += 25
        let img = new Image();
        img.src = URL.createObjectURL(photoInput.files[0]);
        deferPainting = true
        img.addEventListener('load', function() {
            ctx.drawImage(img, 2885, 1060, 569, 758)
            showCanvas(canvas)
        }, false);
    }

    if (!deferPainting)
        showCanvas(canvas)
    let progressBarElem = document.getElementById('progress-bar')
    progressBarElem.style.width = progress + '%'
}

window.onload = () => drawCanvas()