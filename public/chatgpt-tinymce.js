$(window).on("vvveb.tinymce.options", function (e, tinyMceOptions) { 
	tinyMceOptions.quickbars_insert_toolbar += '| AskChatGPT';
	tinyMceOptions.toolbar += '| AskChatGPT';

	return tinyMceOptions;
});

$(window).on("vvveb.tinymce.setup", function (e, editor) { 
	
	editor.ui.registry.addButton('AskChatGPT', {
		text: "Ask ChatGPT",
		icon: 'highlight-bg-color',
		tooltip: 'Highlight a prompt and click this button to query ChatGPT',
		//enabled: true,
		onAction: (_) => {

			//const selection = tinymce.activeEditor.selection.getContent();
			if (!chatgptOptions["key"] ) {
				alert('No ChatGPT key configured! Enter a valid key in the plugin settings page.');
				return;
			}
			
			const selection = prompt('Ask ChatGTP');

			const ChatGPT = {
				api_key: chatgptOptions["key"] ?? null,
				model: chatgptOptions["model"] ?? "text-davinci-003",
				prompt: selection,
				temperature: chatgptOptions["temperature"] ?? 0,
				max_tokens: chatgptOptions["max_tokens"] ?? 70,
				format: "html"
			};

			fetch("https://api.openai.com/v1/completions", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${ChatGPT.api_key}`
				},
				body: JSON.stringify(ChatGPT)
			}).then(res => res.json()).then(data => {
				if (data.error) {
					let message = '';
					for (name in data.error) {
						message += name +":" + data.error[name] + "\n";
					}
					alert(message);
					return;
				}
				var reply = data.choices[0].text;
				//editor.insertContent(reply);
				//editor.execCommand('InsertHTML', false, reply);
				editor.insertContent(reply);
			}).catch(error => {
				console.log("something went wrong", error);
			})
		}
	});
});
