// Styles
import styles from './ProjectManagementForm.module.scss';
// Dependencies
import { useForm } from 'react-hook-form';
import { useMutation } from 'react-query';
// Request
// Components
import { Nav } from '../../../../components/layout/nav/StudentNav/Nav';
// import BarLoader from 'react-spinners/BarLoader';
import { ValidationError } from '../../../../components/utils/validation/ValidationError';
import { DropZone } from '../../../../components/utils/dropzone/DropZone';
import { useEffect, useState } from 'react';

export const ProjectManagementForm = () => {
	const {
		register,
		handleSubmit,
		formState: { errors },
		reset,
	} = useForm();

	const [projectFile, setProjectFile] = useState(null);
	const [projectCover, setProjectCover] = useState(null);

	const onDropProjectFile = (acceptedFiles) => {
		setProjectFile(acceptedFiles[0]);
	};

	const onDropProjectCover = (acceptedFiles) => {
		setProjectCover(acceptedFiles[0]);
	};
	return (
		<main className={styles.main}>
			<Nav />
			<form className={styles.form} onSubmit={handleSubmit}>
				<h1 className={styles.form__title}>Gestionar Proyecto</h1>
				<div>
					<label htmlFor='name' className={styles.form__label}>
						Nombre del proyecto
					</label>
					<input
						className={styles.form__input}
						type='text'
						id='name'
						{...register('name', {
							required: {
								value: true,
								message: 'Este campo es requerido',
							},
						})}
					/>
					{errors.name && <ValidationError message={errors.message.name} />}
				</div>
				<div>
					<label htmlFor='description' className={styles.form__label}>
						Descrpción
					</label>
					<textarea
						className={styles.form__textarea}
						id='description'
						cols='30'
						rows='10'
						{...register('description', {
							required: {
								value: true,
								message: 'La descrpción es requerida',
							},
						})}
					></textarea>
					{errors.description && (
						<ValidationError message={errors.description.message} />
					)}
				</div>
				<div>
					<label className={styles.form__label}>
						Archivo en pdf del proyecto
					</label>
					<DropZone onDrop={onDropProjectFile} accept='.pdf' />
					{projectFile && <p>{projectFile.name}</p>}
				</div>
				<div>
					<label className={styles.form__label}>Portada del proyecto</label>
					<DropZone onDrop={onDropProjectCover} accept='image/*' />
					{projectCover && (
						<img
							src={URL.createObjectURL(projectCover)}
							alt='Portada del proyecto'
						/>
					)}
				</div>
				<button type='submit' className={styles.form__button}>
					Enviar
				</button>
			</form>
		</main>
	);
};
