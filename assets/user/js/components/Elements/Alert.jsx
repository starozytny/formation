import React from "react";
import PropTypes from "prop-types";

export function Alert ({ icon = null, title = null, color, children })
{
	return <div className={`flex flex-row gap-4 bg-${color}-50 text-${color}-700 rounded-md p-6 xl:p-4`}>
		{icon
			? <div><span className="icon-question inline-block align-middle"></span></div>
			: null
		}
		<div>
			{title
				? <div className="font-semibold mb-2 xl:mb-0">{title}</div>
				: null
			}
			<div className="leading-8">{children}</div>
		</div>
	</div>
}


Alert.propTypes = {
	icon: PropTypes.string,
	title: PropTypes.string,
	color: PropTypes.string,
	ring: PropTypes.bool,
}
