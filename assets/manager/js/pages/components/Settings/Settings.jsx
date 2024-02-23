import React from 'react';
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';
import { Taxs } from "@managerPages/Settings/Taxs/Taxs";

const URL_TAX_CREATE = "manager_settings_taxs_create";

export function Settings ({ highlight }) {
	return <>
		<section>
			<div className="page-actions">
				<div className="col-1">
					<h2>Taxes</h2>
					<a href={Routing.generate(URL_TAX_CREATE)} className="btn btn-primary">Ajouter une taxe</a>
				</div>
			</div>
			<div id="taxs_list">
				<Taxs highlight={highlight}/>
			</div>
		</section>
	</>
}
