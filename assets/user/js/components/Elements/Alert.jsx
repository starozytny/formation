import React from "react";

export function AlertBlue ({ icon = null, title = null, children })
{
	return <div className="flex flex-row gap-4 bg-blue-50 text-blue-700 rounded p-6 xl:p-4">
		{icon
			? <div><span className={`icon-question inline-block translate-y-${title ? "0.5" : "1"}`}></span></div>
			: null
		}
		<div>
			{title
				? <div className="font-semibold mb-2 xl:mb-0">Informations</div>
				: null
			}
			<div className="leading-8">{children}</div>
		</div>
	</div>
}
