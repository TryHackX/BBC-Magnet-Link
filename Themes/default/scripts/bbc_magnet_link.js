var abortControllers = [];

function abortActiveRequests() {
	abortControllers.forEach(controller => controller.abort());
	abortControllers = [];
}

function handleMagnetClick(SeedsOnly = false) {
    $('div.magnet_body button#magnet_hook').each(function() {
        const $temp_id_load = $(this).data('temp_id');

        $('a#magnet_hook[data-temp_id="'+$temp_id_load+'"]').html('<div class="loading-text"><span class="loading-text-words">L</span><span class="loading-text-words">O</span><span class="loading-text-words">A</span><span class="loading-text-words">D</span><span class="loading-text-words">I</span><span class="loading-text-words">N</span><span class="loading-text-words">G</span></div>');

		const data = {
			magnet: $('a#magnet_hook[data-temp_id="'+$temp_id_load+'"]').attr('href'),
			temp_id: $('a#magnet_hook[data-temp_id="'+$temp_id_load+'"]').data('temp_id'),

			SeedsOnly: SeedsOnly
		};
		
        const controller = new AbortController();
        abortControllers.push(controller);
		
		const options = {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data),
			signal: controller.signal
		};

		fetch("Sources/Subs-Magnet_Update.php", options)
			.then(response => response.text())
			.then(data => {
				const $tracker_data_split = data.split("	");
				$('a#magnet_hook[data-temp_id="'+$tracker_data_split[0]+'"]').html($tracker_data_split[1]);
			})
			.catch(error => {

			});
    });

    $("div.magnet_body button#magnet_hook").click(function() {
        const $temp_id = $(this).data('temp_id');

        $('a#magnet_hook[data-temp_id="'+$temp_id+'"]').html('<div class="loading-text"><span class="loading-text-words">L</span><span class="loading-text-words">O</span><span class="loading-text-words">A</span><span class="loading-text-words">D</span><span class="loading-text-words">I</span><span class="loading-text-words">N</span><span class="loading-text-words">G</span></div>');

		const data = {
			magnet: $('a#magnet_hook[data-temp_id="'+$temp_id+'"]').attr('href'),
			temp_id: $('a#magnet_hook[data-temp_id="'+$temp_id+'"]').data('temp_id'),

			SeedsOnly: SeedsOnly
		};
		
        const controller = new AbortController();
        abortControllers.push(controller);
		
		const options = {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data),
			signal: controller.signal
		};

		fetch("Sources/Subs-Magnet_Update.php", options)
			.then(response => response.text())
			.then(data => {
				const $tracker_data_split = data.split("	");
				$('a#magnet_hook[data-temp_id="'+$tracker_data_split[0]+'"]').html($tracker_data_split[1]);
			})
			.catch(error => {

			});
    });
}

function Magnet_Link_Count(Magnet_Hash = '', msg_ID = 0) {
	if (Magnet_Hash === '') {
		console.error('BBC_Magnet error: Magnet_Hash is empty.');
		return;
	}
	const data = { Magnet_Hash, msg_ID };
	fetch('Sources/Subs-Magnet_Counter.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify(data),
	})
	.then(response => response.json())
	.then(responseData => {
		if (responseData.status === 'error') {
			console.error('BBC_Magnet error:', responseData.message);
		}
	})
	.catch(error => {
		console.error('BBC_Magnet error:', error);
	});
}

window.addEventListener("beforeunload", function (event) {
    abortActiveRequests();
});

$(function() {
    handleMagnetClick();
});
