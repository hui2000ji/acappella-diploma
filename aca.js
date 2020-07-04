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
        ctx.fillText("" + birth.getFullYear(), 1145, 1270)
        ctx.fillText("" + (birth.getMonth() + 1), 1428, 1270)
        ctx.fillText("" + birth.getDate(), 1685, 1270)
    }

    let photoInput = document.getElementById('input-photo-button')
    if (photoInput.value) {
        progress += 25
        let img = new Image();
        img.src = URL.createObjectURL(photoInput.files[0]);
        img.addEventListener('load', function() {
            ctx.drawImage(img, 2885, 1060, 569, 758)
            showCanvas(canvas)
        }, false);
    }

    let yearInput = document.getElementById('input-year-button')
    if (yearInput.value && yearInput.value <= 2019 && yearInput.value >= 2010) {
        progress += 25
        let signature = document.getElementById('signature-' + yearInput.value)
        ctx.drawImage(signature, 1700, 1920, 525, 393)
    }

    ctx.font = '76px STSong, Noto Serif SC';
    ctx.fillText("2020", 2770, 2265)
    ctx.fillText("7", 3100, 2265)
    ctx.fillText("2", 3320, 2265)

    showCanvas(canvas)
    let progressBarElem = document.getElementById('progress-bar')
    progressBarElem.style.width = progress + '%'
}

window.onload = () => drawCanvas()