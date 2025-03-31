window.onload = () => {
    const logued = backend_data.logued;
    const ajaxUrl = backend_data.ajax_url;
    const nonce = backend_data.nonce;
    const userAgent = navigator.userAgent;

    if (logued) {
        return;
    }
    document.addEventListener('click', function(event) {
        store(event);
    });
    /*document.addEventListener('mousemove', function (event) {
        store(event);
    });*/
    document.addEventListener('touchstart', function (event) {
        store(event);
    });
    /*document.addEventListener('touchend', function (event) {
        store(event);
    });*/

    function store(event) {
        const x = event.clientX;
        const y = event.clientY;
        const screenX = window.innerWidth;
        const screenY = window.innerHeight;
        const timestamp = (new Date()).getTime();
        const cookie = getCookie('min_headmap_uuid_cookie');
        let data = {
            session_id: cookie,
            event: event.type,
            device: userAgent,
            screenX: screenX,
            screenY: screenY,
            coordX: x,
            coordY: y,
            timestamp: timestamp
        };
        if (event.type === 'click') {
            data = newDataClick(cookie, x, y, screenX, screenY, timestamp, event);
        }
        const jsonData = JSON.stringify(data);
        const dataFetcher = new URLSearchParams({
            action: 'min_head_store_data',
            data: jsonData
        });

        if ('undefined' === data.coordX || 'undefined' === data.coordY) {
            return;
        }
        return new Promise((resolve) => {
            fetch(ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-WP-Nonce': nonce
                },
                body: dataFetcher
            })
        }).catch((error) => console.log(error));
    }

    function newDataClick(sessionId, x, y, screenX, screenY, timestamp, event) {
        const clicked = event.target.closest('a');
        const currentUrl = window.location.href;
        if (null !== clicked) {
            const nextUrl = clicked.href;
            window.location.href = nextUrl;
            return {
                session_id: sessionId,
                event: event.type,
                device: userAgent,
                screenX: screenX,
                screenY: screenY,
                coordX: x,
                coordY: y,
                timestamp: timestamp,
                currentUrl: currentUrl,
                nextUrl: nextUrl
            };
        }
        return {
            session_id: sessionId,
            event: event.type,
            device: userAgent,
            screenX: screenX,
            screenY: screenY,
            coordX: x,
            coordY: y,
            timestamp: timestamp,
            currentUrl: currentUrl
        };
    }

    function getCookie(name) {
        const cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            let cookie = cookies[i].trim();
            if (cookie.startsWith(name + '=')) {
                return cookie.substring(name.length + 1);
            }
        }
        return null;
    }
};